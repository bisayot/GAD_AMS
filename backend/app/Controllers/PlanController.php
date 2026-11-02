<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PlanController extends ResourceController
{
    protected $modelName = 'App\Models\GpbModel';
    protected $format    = 'json';

    public function getPlan()
    {
        $db = \Config\Database::connect();
        $requestedYear = (int)($this->request->getGet('fiscal_year') ?: date('Y'));
        $versionService = new \App\Libraries\BudgetVersionService($db);
        $version = $versionService->active($requestedYear);

        // Get organization settings
        $orgData = [
            'name' => 'Benguet State University',
            'category' => 'State Universities and Colleges',
            'hierarchy' => 'Benguet State University',
            'year' => date('Y'),
            'totalOrgBudget' => 1062488000.00,
            'preparedByName' => 'Jude Laoagan Tayaben, GAD Director',
            'approvedByName' => 'Kenneth Alip Laruan, President',
        ];

        if (!$version && $db->tableExists('settings')) {
            $settingModel = new \App\Models\SettingModel();
            // Get the fiscal year of the items currently in the database
            $latestYear = $version['fiscal_year'] ?? $requestedYear;
            $settings = $settingModel->getByFiscalYear($latestYear);
            if (!empty($settings)) {
                // Only merge keys that are expected in orgData or just merge all
                foreach ($orgData as $k => $v) {
                    if ($k !== 'otherSources' && isset($settings[$k])) {
                        $orgData[$k] = $settings[$k];
                    }
                }
            }
        }
        if ($version && !empty($version['organization_data'])) {
            $versionOrganization = json_decode($version['organization_data'], true);
            if (is_array($versionOrganization)) {
                $orgData = array_merge($orgData, $versionOrganization);
            }
        }

        // Ensure year is strictly numeric so frontend number input displays it
        $orgData['year'] = preg_replace('/[^0-9]/', '', (string)$orgData['year']);
        if (empty($orgData['year'])) {
            $orgData['year'] = date('Y');
        }

        $itemQuery = $db->table('gpb_items');
        if ($version) {
            $itemQuery->where('plan_version_id', $version['id']);
        } else {
            $itemQuery->where('fiscal_year', $requestedYear);
        }
        $items = $itemQuery
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();

        // Get actual accomplishment results for each mandate by looking up the linked Activity Designs
        $arDataQuery = $db->table('activity_design_mandates adm')
            ->select('adm.mandate_id as gpb_item_id, ar.id as ar_id, ar.male, ar.female')
            ->join('activity_design ad', 'ad.act_design_id = adm.act_design_id')
            ->join('activity_design_issues adi', 'adi.act_design_id = ad.act_design_id AND adi.issue_id = adm.mandate_id')
            ->join('accomplishment_report ar', 'ar.control_number = ad.control_number')
            ->where('ar.status', 'Verified')
            ->where('ar.deleted_at', null)
            ->get()->getResultArray();
            
            
        $arHeadcounts = [];
        foreach ($arDataQuery as $row) {
            $mandateId = $row['gpb_item_id'];
            $arId = $row['ar_id'];
            if (!isset($arHeadcounts[$mandateId])) {
                $arHeadcounts[$mandateId] = [
                    'male' => 0,
                    'female' => 0,
                    'seen_ars' => []
                ];
            }
            if (!in_array($arId, $arHeadcounts[$mandateId]['seen_ars'])) {
                $arHeadcounts[$mandateId]['male'] += (int)$row['male'];
                $arHeadcounts[$mandateId]['female'] += (int)$row['female'];
                $arHeadcounts[$mandateId]['seen_ars'][] = $arId;
            }
        }

        $arAllocationsQuery = $db->table('budget_item_mandate_allocations bima')
            ->select('bima.mandate_id, bima.gpb_budget_line_id, SUM(bima.allocated_amount) as total_utilized')
            ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
            ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
            ->where('bima.item_type', 'AR')
            ->where('ar.status', 'Verified')
            ->where('ar.deleted_at', null)
            ->groupBy('bima.mandate_id, bima.gpb_budget_line_id')
            ->get()->getResultArray();
            
        $utilizedBudgets = [];
        foreach ($arAllocationsQuery as $row) {
            $mId = $row['mandate_id'];
            $blId = $row['gpb_budget_line_id'];
            if (!isset($utilizedBudgets[$mId])) {
                $utilizedBudgets[$mId] = [];
            }
            $utilizedBudgets[$mId][$blId] = (float)$row['total_utilized'];
        }

        $processedItems = [];
        foreach ($items as $item) {
            $itemId = $item['id'];
            $budgetLines = isset($item['budget_lines']) ? json_decode($item['budget_lines'], true) : [];
            if (!is_array($budgetLines) || empty($budgetLines)) {
                $budgetLines = [];
            } else {
                foreach ($budgetLines as &$bl) {
                    $blId = $bl['id'] ?? null;
                    $bl['utilized_budget'] = 0;
                    if ($blId && isset($utilizedBudgets[$itemId][$blId])) {
                        $bl['utilized_budget'] = $utilizedBudgets[$itemId][$blId];
                    }
                }
                unset($bl);
            }

            $processedItems[] = [
                'id' => (string) $item['id'],
                'item_key' => $item['item_key'] ?? null,
                'section' => $item['section'],
                'mandate' => $item['mandate'],
                'cause' => $item['cause'],
                'result' => $item['result'] ?? $item['objective'],
                'mfo' => $item['mfo'] ?? $item['ppa'],
                'activity' => $item['activity'],
                'indicators' => $item['indicators'] ?? $item['targets'],
                'responsible' => $item['responsible'] ?? $item['office'],
                'budgetLines' => $budgetLines,
                'budget' => (float)($item['budget'] ?? 0),
                'source' => $item['source'] ?? 'GAA',
                'actualResult_m' => isset($arHeadcounts[$item['id']]) ? (int)$arHeadcounts[$item['id']]['male'] : 0,
                'actualResult_f' => isset($arHeadcounts[$item['id']]) ? (int)$arHeadcounts[$item['id']]['female'] : 0,
            ];
        }

        return $this->respond([
            'settings' => ['apiBaseUrl' => ''],
            'org' => $orgData,
            'items' => $processedItems,
            'version' => $version ? [
                'id' => (int)$version['id'],
                'fiscal_year' => (int)$version['fiscal_year'],
                'version_number' => (int)$version['version_number'],
                'status' => $version['status'],
            ] : null,
        ]);
    }

    public function savePlan()
    {
        $payload = $this->request->jwtPayload ?? null;
        if ($payload && !in_array($payload['role'] ?? '', ['admin', 'gad_staff', 'staff', 'superadmin'], true)) {
            return $this->failForbidden('Staff or Administrator access required to save plan.');
        }

        $db = \Config\Database::connect();
        $input = $this->request->getJSON(true);

        // Validate input
        if (!isset($input['org']) || !isset($input['items'])) {
            return $this->failValidationErrors('Org and items data are required');
        }

        $year = (int)($input['org']['year'] ?? 0);
        if ($year < 2000 || $year > 2100) {
            return $this->failValidationErrors('A valid fiscal year is required.');
        }
        try {
            $service = new \App\Libraries\BudgetVersionService($db);
            $actorId = isset($payload['uid']) ? (int)$payload['uid'] : (isset($payload['id']) ? (int)$payload['id'] : null);
            $version = $service->saveInPlace($year, $input['org'], $input['items'], $actorId);
            return $this->respond(['status' => 'success', 'message' => 'Plan saved successfully', 'version' => $version]);
        } catch (\InvalidArgumentException|\RuntimeException $e) {
            return $this->failValidationErrors($e->getMessage());
        }

        return $this->failServerError('Plan save did not complete.');
    }

    public function resetPlan()
    {
        $payload = $this->request->jwtPayload ?? [];
        $role = strtolower(trim((string)($payload['role'] ?? $payload['user_role'] ?? '')));
        $normalizedRole = str_replace(['-', ' '], '_', $role);
        if (!in_array($normalizedRole, ['admin', 'director', 'gad_staff', 'staff', 'superadmin'], true)) {
            return $this->failForbidden('Staff or Administrator access required to reset the plan.');
        }
        $input = $this->request->getJSON(true) ?: [];
        $year = (int)($input['year'] ?? $this->request->getGet('fiscal_year') ?? date('Y'));
        if ($year < 2000 || $year > 2100) {
            return $this->failValidationErrors('A valid fiscal year is required.');
        }

        $currentCalendarYear = (int)date('Y');
        if ($year < $currentCalendarYear) {
            return $this->failForbidden('Fiscal Year ' . $year . ' is a closed and archived period. You cannot reset past fiscal years.');
        }

        $isEarlyReset = !empty($input['early_reset']);

        try {
            $actorId = isset($payload['uid']) ? (int)$payload['uid'] : (isset($payload['id']) ? (int)$payload['id'] : null);
            $version = (new \App\Libraries\BudgetVersionService())->createReplacement(
                $year,
                is_array($input['org'] ?? null) ? $input['org'] : ['year' => $year],
                [],
                'reset',
                $actorId
            );

            if ($actorId) {
                $userModel = new \App\Models\UserModel();
                $actor = $userModel->find($actorId);
                $actorName = $actor['full_name'] ?? 'User';
                $logAction = $isEarlyReset ? 'Early Plan Reset' : 'Plan Reset';
                $logDetails = $isEarlyReset 
                    ? "{$actorName} performed an authorized Early Reset on active Fiscal Year {$year} after completing 3 security human verification challenges."
                    : "{$actorName} reset the plan for Fiscal Year {$year}.";
                \App\Models\ActivityLogModel::log($actorId, $logAction, $logDetails);
            }

            return $this->respond(['status' => 'success', 'message' => 'A new empty plan version is active.', 'version' => $version]);
        } catch (\Throwable $e) {
            log_message('error', 'Plan reset failed: {message} in {file}:{line}', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->failServerError('Plan reset failed: ' . $e->getMessage());
        }
    }

    public function getMandateStatistics()
    {
        $db = \Config\Database::connect();
        $fiscalYear = (int)($this->request->getGet('fiscal_year') ?: date('Y'));
        $version = $db->tableExists('budget_versions')
            ? (new \App\Libraries\BudgetVersionService($db))->active($fiscalYear)
            : null;
        
        // 1. Get all GPB Items
        $gpbQuery = $db->table('gpb_items');
        if ($version) {
            $gpbQuery->where('plan_version_id', $version['id']);
        } else {
            $gpbQuery->where('fiscal_year', $fiscalYear);
        }
        $gpbItems = $gpbQuery->get()->getResultArray();
        
        $mandateStats = [];
        
        // Group by mandate + cause + activity to ensure distinct entries
        foreach($gpbItems as $item) {
            $mandateName = trim($item['mandate']);
            $causeName = trim($item['cause']);
            $activityName = trim($item['activity']);
            
            if (empty($mandateName)) {
                $mandateName = $activityName ?: 'Attributed Program / Unspecified';
            }
            
            // Unique key for grouping
            $groupKey = md5($mandateName . '|' . $causeName . '|' . $activityName);
            
            if (!isset($mandateStats[$groupKey])) {
                $mandateStats[$groupKey] = [
                    'key' => $groupKey,
                    'classification' => $item['section'] ?? '',
                    'mandate' => $mandateName,
                    'cause' => $causeName,
                    'activity' => $activityName,
                    'budget' => 0.0,
                    'approved_ad_count' => 0,
                    'approved_ar_count' => 0,
                    'remaining_budget' => 0.0,
                    'utilized_budget' => 0.0,
                    'pending_budget' => 0.0,
                    'gpb_ids' => [],
                    'lineage_ids' => [],
                    'budget_lines' => []
                ];
            }
            
            // Add budget
            $mandateStats[$groupKey]['budget'] += (float)$item['budget'];
            $mandateStats[$groupKey]['gpb_ids'][] = (int)$item['id'];
            $lineageIds = [$item['id']];
            if (!empty($item['item_key'])) {
                $lineageIds = array_column(
                    $db->table('gpb_items')
                        ->select('id')
                        ->where('item_key', $item['item_key'])
                        ->where('fiscal_year', $fiscalYear)
                        ->get()
                        ->getResultArray(),
                    'id'
                );
            }
            $mandateStats[$groupKey]['lineage_ids'] = array_values(array_unique(array_merge(
                $mandateStats[$groupKey]['lineage_ids'],
                array_map('intval', $lineageIds)
            )));

            // Parse budget lines
            $bLines = isset($item['budget_lines']) ? json_decode($item['budget_lines'], true) : [];
            if (is_array($bLines)) {
                foreach ($bLines as $bl) {
                    $bl['pending_budget'] = 0.0;
                    $bl['utilized_budget'] = 0.0;
                    $mandateStats[$groupKey]['budget_lines'][] = $bl;
                }
            }
        }
        
        // Now calculate AD and AR for each group
        foreach($mandateStats as &$stat) {
            if (empty($stat['gpb_ids'])) {
                continue;
            }
            $lineageIds = $stat['lineage_ids'] ?: $stat['gpb_ids'];
            
            // Get all Approved ADs linked to these gpb_ids
            $adLinks = $db->table('activity_design_mandates adm')
                ->select('ad.act_design_id, ad.proposed_budget, ad.control_number')
                ->join('activity_design ad', 'ad.act_design_id = adm.act_design_id')
                ->join('activity_design_issues adi', 'adi.act_design_id = ad.act_design_id AND adi.issue_id = adm.mandate_id')
                ->whereIn('adm.mandate_id', $lineageIds)
                ->where('ad.status', 'Approved')
                ->where('ad.deleted_at', null)
                ->groupBy('ad.act_design_id')
                ->get()->getResultArray();
                
            $stat['approved_ad_count'] = count($adLinks);
            
            foreach($adLinks as $ad) {
                // Check AR
                $ar = $db->table('accomplishment_report')
                    ->where('control_number', $ad['control_number'])
                    ->where('status', 'Verified')
                    ->where('deleted_at', null)
                    ->get()->getRowArray();
                    
                if ($ar) {
                    $stat['approved_ar_count']++;
                    
                    $arAllocationsExist = $db->table('budget_item_mandate_allocations bima')
                        ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
                        ->where('bima.item_type', 'AR')
                        ->where('abi.accomplishment_report_id', $ar['id'])
                        ->countAllResults() > 0;
                        
                    if ($arAllocationsExist) {
                        $manualCostRow = $db->table('budget_item_mandate_allocations bima')
                            ->selectSum('bima.allocated_amount')
                            ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
                            ->where('bima.item_type', 'AR')
                            ->where('abi.accomplishment_report_id', $ar['id'])
                            ->whereIn('bima.mandate_id', $lineageIds)
                            ->get()->getRowArray();
                        $actualCost = $manualCostRow ? (float)$manualCostRow['allocated_amount'] : 0.0;
                    } else {
                        // Fallback to AD allocations if AR is unassigned
                        $manualPendingRow = $db->table('budget_item_mandate_allocations bima')
                            ->selectSum('bima.allocated_amount')
                            ->join('activity_budget_items abi', 'abi.id = bima.budget_item_id')
                            ->where('bima.item_type', 'AD')
                            ->where('abi.act_design_id', $ad['act_design_id'])
                            ->whereIn('bima.mandate_id', $lineageIds)
                            ->get()->getRowArray();
                        $actualCost = $manualPendingRow ? (float)$manualPendingRow['allocated_amount'] : 0.0;
                    }
                    
                    $stat['utilized_budget'] += $actualCost;
                } else {
                    // Sum only manually allocated activity_budget_items for this specific group of mandates
                    $manualPendingRow = $db->table('budget_item_mandate_allocations bima')
                        ->selectSum('bima.allocated_amount')
                        ->join('activity_budget_items abi', 'abi.id = bima.budget_item_id')
                        ->where('bima.item_type', 'AD')
                        ->where('abi.act_design_id', $ad['act_design_id'])
                        ->whereIn('bima.mandate_id', $lineageIds)
                        ->get()->getRowArray();
                        
                    $pendingCost = $manualPendingRow ? (float)$manualPendingRow['allocated_amount'] : 0.0;
                    $stat['pending_budget'] += $pendingCost;
                }
            }
            // Now, populate per-budget-line totals
            // For ARs (Utilized)
            $arLineCosts = $db->table('budget_item_mandate_allocations bima')
                ->select('bima.gpb_budget_line_id, SUM(bima.allocated_amount) as total_utilized')
                ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
                ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
                ->where('bima.item_type', 'AR')
                ->whereIn('bima.mandate_id', $lineageIds)
                ->where('ar.status', 'Verified')
                ->where('ar.deleted_at', null)
                ->where('bima.gpb_budget_line_id !=', null)
                ->groupBy('bima.gpb_budget_line_id')
                ->get()->getResultArray();

            foreach ($arLineCosts as $row) {
                foreach ($stat['budget_lines'] as &$bl) {
                    if ($bl['id'] === $row['gpb_budget_line_id']) {
                        $bl['utilized_budget'] += (float)$row['total_utilized'];
                    }
                }
            }

            // For ADs (Pending)
            // Note: AD is considered pending if there is NO verified AR for it.
            $adLineCosts = $db->table('budget_item_mandate_allocations bima')
                ->select('bima.gpb_budget_line_id, ad.control_number, bima.allocated_amount')
                ->join('activity_budget_items abi', 'abi.id = bima.budget_item_id')
                ->join('activity_design ad', 'ad.act_design_id = abi.act_design_id')
                ->where('bima.item_type', 'AD')
                ->whereIn('bima.mandate_id', $lineageIds)
                ->where('ad.status', 'Approved')
                ->where('ad.deleted_at', null)
                ->where('bima.gpb_budget_line_id !=', null)
                ->get()->getResultArray();

            foreach ($adLineCosts as $row) {
                // Check if this AD has a verified AR
                $hasVerifiedAR = $db->table('accomplishment_report')
                    ->where('control_number', $row['control_number'])
                    ->where('status', 'Verified')
                    ->where('deleted_at', null)
                    ->countAllResults() > 0;

                if (!$hasVerifiedAR) {
                    foreach ($stat['budget_lines'] as &$bl) {
                        if ($bl['id'] === $row['gpb_budget_line_id']) {
                            $bl['pending_budget'] += (float)$row['allocated_amount'];
                        }
                    }
                }
            }

            $stat['remaining_budget'] = $stat['budget'] - $stat['utilized_budget'] - $stat['pending_budget'];
            // Do not unset gpb_ids; frontend needs it for manual allocations
        }
        
        // Re-index array
        $finalStats = array_values($mandateStats);
        
        return $this->respond([
            'success' => true,
            'data' => $finalStats
        ]);
    }

    public function getMandateAllocations()
    {
        $gpbIds = $this->request->getGet('gpb_ids');
        if (empty($gpbIds)) return $this->fail('gpb_ids required');
        $gpbIds = explode(',', $gpbIds);

        $db = \Config\Database::connect();
        $selectedRows = $db->table('gpb_items')->select('item_key, fiscal_year')->whereIn('id', $gpbIds)->get()->getResultArray();
        $lineageKeys = array_values(array_filter(array_column($selectedRows, 'item_key')));
        $selectedYears = array_values(array_unique(array_filter(array_map('intval', array_column($selectedRows, 'fiscal_year')))));
        $lineageQuery = $db->table('gpb_items')->select('id')->whereIn('id', $gpbIds);
        if ($lineageKeys) {
            $lineageQuery->orGroupStart()
                ->whereIn('item_key', $lineageKeys);
            if ($selectedYears) {
                $lineageQuery->whereIn('fiscal_year', $selectedYears);
            }
            $lineageQuery->groupEnd();
        }
        $lineageIds = $lineageQuery->get()->getResultArray();
        $lineageIds = array_values(array_unique(array_map('intval', array_column($lineageIds, 'id'))));
        if (!$lineageIds) {
            $lineageIds = array_map('intval', $gpbIds);
        }

        $adLinks = $db->table('activity_design_mandates adm')
            ->select('ad.act_design_id, ad.activity_title as title, ad.control_number, ad.attachment')
            ->join('activity_design ad', 'ad.act_design_id = adm.act_design_id')
            ->join('activity_design_issues adi', 'adi.act_design_id = ad.act_design_id')
            ->whereIn('adm.mandate_id', $lineageIds)
            ->whereIn('adi.issue_id', $lineageIds)
            ->where('ad.status', 'Approved')
            ->where('ad.deleted_at', null)
            ->groupBy('ad.act_design_id')
            ->get()->getResultArray();

        $documents = [];

        foreach($adLinks as $ad) {
            $ar = $db->table('accomplishment_report')
                ->where('control_number', $ad['control_number'])
                ->where('status', 'Verified')
                ->where('deleted_at', null)
                ->get()->getRowArray();

            if ($ar) {
                $items = $db->table('accomplishment_budget_items')
                    ->where('accomplishment_report_id', $ar['id'])
                    ->get()->getResultArray();
                
                foreach ($items as &$item) {
                    $allocations = $db->table('budget_item_mandate_allocations')
                        ->where('item_type', 'AR')
                        ->where('budget_item_id', $item['id'])
                        ->get()->getResultArray();
                    
                    foreach ($allocations as &$al) {
                        $gpbItem = $db->table('gpb_items')
                            ->select('mandate, cause, activity')
                            ->where('id', $al['mandate_id'])
                            ->get()->getRowArray();
                        if ($gpbItem) {
                            $mName = trim($gpbItem['mandate']);
                            $al['mandate_name'] = $mName ? $mName : ($gpbItem['activity'] ?: 'Attributed Program');
                            $al['cause_name'] = trim($gpbItem['cause']);
                            $al['activity_name'] = trim($gpbItem['activity']);
                        } else {
                            $al['mandate_name'] = 'Unknown Mandate';
                            $al['cause_name'] = '';
                            $al['activity_name'] = '';
                        }
                    }
                    
                    $item['allocations'] = $allocations;
                    
                    $allocatedToCurrent = 0;
                    $allocatedGpbLineId = null;
                    foreach ($allocations as $al) {
                        if (in_array($al['mandate_id'], $lineageIds)) {
                            $allocatedToCurrent += (float)$al['allocated_amount'];
                            if (!empty($al['gpb_budget_line_id'])) {
                                $allocatedGpbLineId = $al['gpb_budget_line_id'];
                            }
                        }
                    }
                    $item['allocated_to_current'] = $allocatedToCurrent;
                    $item['gpb_budget_line_id'] = $allocatedGpbLineId;
                }

                $documents[] = [
                    'type' => 'AR',
                    'id' => $ar['id'],
                    'title' => 'AR for ' . ($ad['title'] ?? $ad['control_number']),
                    'control_number' => $ar['control_number'],
                    'attachment' => $ar['attachment'],
                    'items' => $items
                ];
            } 
            
            // Only show the AD if it does NOT have a verified AND archived AR
            $hasVerifiedAndArchivedAR = ($ar && $ar['is_archived'] == 1);
            
            if (!$hasVerifiedAndArchivedAR) {
                $items = $db->table('activity_budget_items')
                    ->where('act_design_id', $ad['act_design_id'])
                    ->get()->getResultArray();

                foreach ($items as &$item) {
                    $allocations = $db->table('budget_item_mandate_allocations')
                        ->where('item_type', 'AD')
                        ->where('budget_item_id', $item['id'])
                        ->get()->getResultArray();
                    $item['allocations'] = $allocations;
                    
                    $allocatedToCurrent = 0;
                    $allocatedGpbLineId = null;
                    foreach ($allocations as $al) {
                        if (in_array($al['mandate_id'], $lineageIds)) {
                            $allocatedToCurrent += (float)$al['allocated_amount'];
                            if (!empty($al['gpb_budget_line_id'])) {
                                $allocatedGpbLineId = $al['gpb_budget_line_id'];
                            }
                        }
                    }
                    $item['allocated_to_current'] = $allocatedToCurrent;
                    $item['gpb_budget_line_id'] = $allocatedGpbLineId;
                }

                $documents[] = [
                    'type' => 'AD',
                    'id' => $ad['act_design_id'],
                    'title' => $ad['title'],
                    'control_number' => $ad['control_number'],
                    'attachment' => $ad['attachment'],
                    'items' => $items
                ];
            }
        }

        return $this->respond([
            'success' => true,
            'data' => $documents
        ]);
    }

    public function saveMandateAllocations()
    {
        $input = $this->request->getJSON(true);
        $gpbIds = $input['gpb_ids'] ?? [];
        $allocations = $input['allocations'] ?? [];

        if (empty($gpbIds)) return $this->fail('gpb_ids required');

        $db = \Config\Database::connect();
        
        $targetMandateId = $gpbIds[0];

        foreach ($allocations as $alloc) {
            $itemId = $alloc['budget_item_id'];
            $itemType = $alloc['item_type'];
            $amount = (float)$alloc['allocated_amount'];
            $gpbLineId = $alloc['gpb_budget_line_id'] ?? null;

            // Find matching AR item if this is an AD item
            $arItemIdToSync = null;
            $arItemAmount = 0;
            if ($itemType === 'AD') {
                $adItem = $db->table('activity_budget_items')->where('id', $itemId)->get()->getRowArray();
                if ($adItem) {
                    $ad = $db->table('activity_design')->where('act_design_id', $adItem['act_design_id'])->get()->getRowArray();
                    if ($ad) {
                        $ar = $db->table('accomplishment_report')
                                 ->where('control_number', $ad['control_number'])
                                 ->where('status', 'Verified')
                                 ->where('deleted_at', null)
                                 ->get()->getRowArray();
                        if ($ar) {
                            $arItemQuery = $db->table('accomplishment_budget_items')
                                         ->where('accomplishment_report_id', $ar['id'])
                                         ->where('item_name', $adItem['item_name']);
                            if (empty($adItem['sub_item'])) {
                                $arItemQuery->groupStart()
                                            ->where('sub_item', null)
                                            ->orWhere('sub_item', '')
                                            ->groupEnd();
                            } else {
                                $arItemQuery->where('sub_item', $adItem['sub_item']);
                            }
                            $arItem = $arItemQuery->get()->getRowArray();
                            if ($arItem) {
                                $arItemIdToSync = $arItem['id'];
                                $arItemAmount = (float)$arItem['amount'];
                            }
                        }
                    }
                }
            }

            $db->table('budget_item_mandate_allocations')
               ->where('budget_item_id', $itemId)
               ->where('item_type', $itemType)
               ->whereIn('mandate_id', $gpbIds)
               ->delete();

            if ($arItemIdToSync) {
                $db->table('budget_item_mandate_allocations')
                   ->where('budget_item_id', $arItemIdToSync)
                   ->where('item_type', 'AR')
                   ->whereIn('mandate_id', $gpbIds)
                   ->delete();
            }

            if ($amount > 0) {
                $db->table('budget_item_mandate_allocations')->insert([
                    'budget_item_id' => $itemId,
                    'item_type' => $itemType,
                    'mandate_id' => $targetMandateId,
                    'gpb_budget_line_id' => $gpbLineId,
                    'allocated_amount' => $amount,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($arItemIdToSync && $arItemAmount > 0) {
                    $db->table('budget_item_mandate_allocations')->insert([
                        'budget_item_id' => $arItemIdToSync,
                        'item_type' => 'AR',
                        'mandate_id' => $targetMandateId,
                        'gpb_budget_line_id' => $gpbLineId,
                        'allocated_amount' => $arItemAmount,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        return $this->respond(['success' => true]);
    }

    /**
     * Get live summary of items and verified accomplishments before resetting plan
     */
    public function preResetSummary()
    {
        $db = \Config\Database::connect();
        $requestedYear = (int)($this->request->getGet('fiscal_year') ?: date('Y'));
        $versionService = new \App\Libraries\BudgetVersionService($db);
        $activeVersion = $versionService->active($requestedYear);

        $itemCount = 0;
        $totalProposed = 0.0;
        $adCount = 0;
        $arCount = 0;
        $totalUtilized = 0.0;

        if ($activeVersion) {
            $items = $db->table('gpb_items')
                ->where('plan_version_id', $activeVersion['id'])
                ->get()
                ->getResultArray();
            $itemCount = count($items);
            foreach ($items as $item) {
                $totalProposed += (float)($item['total_budget'] ?? 0);
            }

            $adRow = $db->table('activity_design_mandates adm')
                ->join('gpb_items gi', 'gi.id = adm.mandate_id')
                ->join('activity_design ad', 'ad.act_design_id = adm.act_design_id')
                ->where('gi.plan_version_id', $activeVersion['id'])
                ->where('ad.status', 'Approved')
                ->where('ad.deleted_at', null)
                ->select('COUNT(DISTINCT ad.act_design_id) as ad_count')
                ->get()
                ->getRowArray();
            $adCount = (int)($adRow['ad_count'] ?? 0);

            $arRow = $db->table('budget_item_mandate_allocations bima')
                ->join('gpb_items gi', 'gi.id = bima.mandate_id')
                ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
                ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
                ->where('gi.plan_version_id', $activeVersion['id'])
                ->where('bima.item_type', 'AR')
                ->where('ar.status', 'Verified')
                ->where('ar.deleted_at', null)
                ->select('COUNT(DISTINCT ar.id) as ar_count, COALESCE(SUM(bima.allocated_amount), 0) as total_utilized')
                ->get()
                ->getRowArray();
            $arCount = (int)($arRow['ar_count'] ?? 0);
            $totalUtilized = (float)($arRow['total_utilized'] ?? 0.0);
        }

        return $this->respond([
            'fiscal_year' => $requestedYear,
            'version_number' => (int)($activeVersion['version_number'] ?? 1),
            'next_version_number' => (int)(($activeVersion['version_number'] ?? 1) + 1),
            'item_count' => $itemCount,
            'ad_count' => $adCount,
            'total_proposed' => $totalProposed,
            'ar_count' => $arCount,
            'total_utilized' => $totalUtilized,
        ]);
    }
}
