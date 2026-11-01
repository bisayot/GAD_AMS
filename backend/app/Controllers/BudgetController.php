<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class BudgetController extends Controller
{
    use ResponseTrait;

    /**
     * Get overall budget utilization summary metrics.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getSummary()
    {
        $db = \Config\Database::connect();
        $fiscalYear = (int)($this->request->getGet('fiscal_year') ?: date('Y'));
        $version = null;
        if ($db->tableExists('budget_versions')) {
            $version = (new \App\Libraries\BudgetVersionService($db))->active($fiscalYear);
        }
        $itemsQuery = $db->table('gpb_items');
        if ($version) {
            $itemsQuery->where('plan_version_id', $version['id']);
        } else {
            $itemsQuery->where('fiscal_year', $fiscalYear);
        }
        $items = $itemsQuery->get()->getResultArray();
        $gaaBudget = 0.0;
        $lineOtherSources = 0.0;
        foreach ($items as $item) {
            $budgetLines = isset($item['budget_lines']) ? json_decode($item['budget_lines'], true) : [];
            if (is_array($budgetLines)) {
                foreach ($budgetLines as $line) {
                    $amount = (float)($line['amount'] ?? 0);
                    if (strtolower(trim((string)($line['source'] ?? 'GAA'))) === 'other') {
                        $lineOtherSources += $amount;
                    } else {
                        $gaaBudget += $amount;
                    }
                }
            }
        }

        $totalBudget = $gaaBudget + $lineOtherSources;

        $lineageIds = [];
        if ($version) {
            $activeKeys = array_values(array_filter(array_column($items, 'item_key')));
            if ($activeKeys) {
                $lineageIds = array_column(
                    $db->table('gpb_items')->select('id')->whereIn('item_key', $activeKeys)->get()->getResultArray(),
                    'id'
                );
            }
        } else {
            $lineageIds = array_column($items, 'id');
        }

        $approvedDesignQuery = $db->table('activity_design ad')
            ->select('ad.*')
            ->join('activity_design_mandates adm', 'adm.act_design_id = ad.act_design_id', 'left')
            ->groupStart();
        if ($lineageIds) {
            $approvedDesignQuery
                ->whereIn('adm.mandate_id', $lineageIds)
                ->orWhereIn('ad.gpb_id', $lineageIds);
        } else {
            $approvedDesignQuery->where('ad.act_design_id', 0);
        }
        $approvedDesigns = $approvedDesignQuery
            ->groupEnd()
            ->where('ad.status', 'Approved')
            ->where('ad.deleted_at', null)
            ->groupBy('ad.act_design_id')
            ->get()
            ->getResultArray();

        $utilized = 0.0;
        $pendingApproved = 0.0;

        foreach ($approvedDesigns as $design) {

            // Check for completed/verified accomplishment report (active or archived)
            $report = $db->table('accomplishment_report')
                ->where('control_number', $design['control_number'])
                ->whereIn('status', ['Completed', 'Verified', 'Approved'])
                ->where('deleted_at', null)
                ->get()
                ->getRowArray();

            if ($report) {
                $reportId = $report['id'];
                $table = 'accomplishment_budget_items';

                $sum = $db->table($table)
                    ->selectSum('amount')
                    ->where('accomplishment_report_id', $reportId)
                    ->get()
                    ->getRow()->amount ?? 0.0;

                $utilized += (float) $sum;
            } else {
                $pendingApproved += (float) $design['proposed_budget'];
            }
        }

        $totalBudget = (float) $totalBudget;
        $remainingBalance = $totalBudget - $utilized - $pendingApproved;
        $utilizationRate = $totalBudget > 0 ? ($utilized / $totalBudget) * 100 : 0.0;

        return $this->respond([
            'success' => true,
            'data' => [
                'total_budget'            => $totalBudget,
                'total_utilized'          => $utilized,
                'total_pending_approved'  => $pendingApproved,
                'remaining_balance'       => $remainingBalance,
                'utilization_rate'        => $utilizationRate
            ]
        ]);
    }

    /**
     * Get the dynamic GAD Plan Budget rows grouped and compiled with breakdown source metadata.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getGadPlan()
    {
        $db = \Config\Database::connect();
        $fiscalYear = (int)($this->request->getGet('fiscal_year') ?: date('Y'));
        $version = $db->tableExists('budget_versions')
            ? (new \App\Libraries\BudgetVersionService($db))->active($fiscalYear)
            : null;
        
        // Aliasing the new columns to match the old expected JSON response for the frontend
        $query = $db->table('gpb_items');
        if ($version) {
            $query->where('plan_version_id', $version['id']);
        } else {
            $query->where('fiscal_year', $fiscalYear);
        }
        $rows = $query
            ->select('
                id as gpb_id,
                mandate as gender_issue_mandate,
                cause as cause_of_gender_issue,
                objective as gad_result_objective,
                mfo as relevant_org_mfo_pap,
                activity as gad_activity,
                targets as performance_indicators_targets,
                budget as gad_budget,
                source as source_of_budget,
                responsible as responsible_unit_office
            ')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['gad_budget'] = (float)$row['gad_budget'];
        }

        return $this->respond([
            'success' => true,
            'data'    => $rows
        ]);
    }

    /**
     * Get real-time GPB mandate budget utilization monitoring data.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getOfficeUtilization()
    {
        $db = \Config\Database::connect();
        $fiscalYear = $this->request->getGet('fiscal_year');

        // Fetch distinct fiscal years for switcher
        $yearsQuery = $db->table('gpb_items')
            ->select('DISTINCT(fiscal_year) as year')
            ->where('fiscal_year IS NOT NULL')
            ->where('fiscal_year !=', '')
            ->orderBy('fiscal_year', 'DESC');
        if ($db->tableExists('budget_versions')) {
            $yearsQuery = $db->table('budget_versions')
                ->select('DISTINCT(fiscal_year) as year')
                ->where('fiscal_year IS NOT NULL')
                ->orderBy('fiscal_year', 'DESC');
        }
        $yearsRows = $yearsQuery->get()->getResultArray();
        $availableYears = array_values(array_filter(array_map(function($r) {
            return !empty($r['year']) ? (string)$r['year'] : null;
        }, $yearsRows)));
        if (empty($availableYears)) {
            $availableYears = [(string)date('Y')];
        }

        $selectedYear = (!empty($fiscalYear) && $fiscalYear !== 'all')
            ? (int)$fiscalYear
            : (int)($availableYears[0] ?? date('Y'));
        $version = $db->tableExists('budget_versions')
            ? (new \App\Libraries\BudgetVersionService($db))->active($selectedYear)
            : null;

        $gpbQuery = $db->table('gpb_items');
        if ($version) {
            $gpbQuery->where('plan_version_id', $version['id']);
        } else {
            $gpbQuery->where('fiscal_year', $selectedYear);
        }
        $gpbs = $gpbQuery->orderBy('id', 'ASC')->get()->getResultArray();
        $restoredSnapshotRows = [];
        if ($version && ($version['source'] ?? '') === 'restore' && $db->tableExists('budget_version_monitoring_snapshots')) {
            $snapshot = $db->table('budget_version_monitoring_snapshots')
                ->where('plan_version_id', $version['id'])->get()->getRowArray();
            foreach (json_decode($snapshot['rows_data'] ?? '[]', true) ?: [] as $snapshotRow) {
                if (!empty($snapshotRow['item_key'])) {
                    $restoredSnapshotRows[$snapshotRow['item_key']] = $snapshotRow;
                }
            }
        }
        $budgetRows = [];

        foreach ($gpbs as $gpb) {
            $gpbId = $gpb['id'];
            $allocated = (float) $gpb['budget'];
            $lineageIds = [$gpbId];
            if (!empty($gpb['item_key'])) {
                $lineageIds = array_column(
                    $db->table('gpb_items')->select('id')->where('item_key', $gpb['item_key'])->get()->getResultArray(),
                    'id'
                );
            }
            $lineageIds = array_values(array_unique(array_map('intval', $lineageIds)));

            // Get approved activity designs for this mandate (GPB item)
            // Check both modern activity_design_mandates and direct gpb_id
            $designs = $db->table('activity_design ad')
                ->select('ad.act_design_id, ad.control_number, ad.activity_title, ad.proposed_budget, COALESCE(ou.office_acronym, ou.office_name, u.username, "") as college, COALESCE(ad.start_date, "") as created_at, ad.attachment')
                ->join('users u', 'u.id = ad.user_id', 'left')
                ->join('office_units ou', 'ou.office_id = u.office_id', 'left')
                ->join('activity_design_mandates adm', 'adm.act_design_id = ad.act_design_id', 'left')
                ->join('activity_design_issues adi', 'adi.act_design_id = ad.act_design_id', 'left')
                ->groupStart()
                    ->groupStart()
                        ->whereIn('adm.mandate_id', $lineageIds)
                        ->groupStart()
                            ->whereIn('adi.issue_id', $lineageIds)
                            ->orWhere('adi.issue_id IS NULL')
                        ->groupEnd()
                    ->groupEnd()
                    ->orWhereIn('ad.gpb_id', $lineageIds)
                ->groupEnd()
                ->where('ad.status', 'Approved')
                ->where('ad.deleted_at', null)
                ->groupBy('ad.act_design_id')
                ->get()
                ->getResultArray();

            $utilized = 0.0;
            $pendingApproved = 0.0;
            $pendingAds = [];
            $completedArs = [];

            foreach ($designs as $design) {
                // Check for a completed accomplishment report (active or archived)
                $report = $db->table('accomplishment_report ar')
                    ->select('ar.*, COALESCE(ou_ar.office_acronym, ou_ar.office_name, u_ar.username, "") as college, COALESCE(ar.start_date, "") as report_date')
                    ->join('users u_ar', 'u_ar.id = ar.user_id', 'left')
                    ->join('office_units ou_ar', 'ou_ar.office_id = u_ar.office_id', 'left')
                    ->where('ar.control_number', $design['control_number'])
                    ->whereIn('ar.status', ['Completed', 'Verified', 'Approved'])
                    ->where('ar.deleted_at', null)
                    ->get()
                    ->getRowArray();

                if ($report) {
                    // Use actual spending total from accomplishment_budget_items
                    $reportId = $report['id'];
                    $actualTotalRow = $db->table('accomplishment_budget_items')
                        ->select('SUM(amount) as total')
                        ->where('accomplishment_report_id', $reportId)
                        ->get()
                        ->getRowArray();

                    $arAmount = 0.0;
                    if ($actualTotalRow && $actualTotalRow['total'] !== null) {
                        $arAmount = (float)$actualTotalRow['total'];
                    } else {
                        $arAmount = (float)$design['proposed_budget'];
                    }
                    $utilized += $arAmount;

                    $completedArs[] = [
                        'id'             => $reportId,
                        'control_number' => $report['control_number'],
                        'title'          => !empty($report['activity_title']) ? $report['activity_title'] : (!empty($design['activity_title']) ? $design['activity_title'] : 'Accomplishment Report'),
                        'amount'         => $arAmount,
                        'college'        => !empty($report['college']) ? $report['college'] : ($design['college'] ?? ''),
                        'created_at'     => $report['report_date'] ?? '',
                        'attachment'     => $report['attachment'] ?? null
                    ];
                } else {
                    // No completed report yet, so it counts as pending approved commitment
                    $adAmount = (float) $design['proposed_budget'];
                    $pendingApproved += $adAmount;

                    $pendingAds[] = [
                        'id'             => $design['act_design_id'],
                        'control_number' => $design['control_number'],
                        'title'          => !empty($design['activity_title']) ? $design['activity_title'] : 'Activity Design',
                        'amount'         => $adAmount,
                        'college'        => $design['college'] ?? '',
                        'created_at'     => $design['created_at'] ?? '',
                        'attachment'     => $design['attachment'] ?? null
                    ];
                }
            }

            $remaining = $allocated - $utilized - $pendingApproved;  // can be negative (overspent)
            $utilizationRate = $allocated > 0 ? ($utilized / $allocated) * 100 : 0.0;
            $restoredRow = $restoredSnapshotRows[$gpb['item_key'] ?? ''] ?? null;
            if ($restoredRow) {
                $utilized = (float)($restoredRow['utilized'] ?? $utilized);
                $pendingApproved = (float)($restoredRow['pending_approved'] ?? $pendingApproved);
                $remaining = (float)($restoredRow['remaining'] ?? ($allocated - $utilized - $pendingApproved));
                $utilizationRate = (float)($restoredRow['utilization_rate'] ?? ($allocated > 0 ? ($utilized / $allocated) * 100 : 0.0));
            }

            $budgetRows[] = [
                'id'               => $gpbId,
                'mandate'          => $gpb['mandate'],
                'activity'         => $gpb['activity'],
                'section'          => $gpb['section'] ?? '',
                'responsible'      => $gpb['responsible'] ?? '',
                'fiscal_year'      => $gpb['fiscal_year'] ?? '',
                'unit_code'        => 'GPB-' . $gpbId,
                'allocated'        => $allocated,
                'utilized'         => $utilized,
                'actual_cost'      => $utilized,
                'pending_approved' => $pendingApproved,
                'remaining'        => $remaining,
                'utilizationRate'  => $utilizationRate,
                'pending_ads'      => $pendingAds,
                'completed_ars'    => $completedArs,
                'total_docs_count' => count($pendingAds) + count($completedArs)
            ];
        }

        return $this->respond([
            'success'         => true,
            'data'            => $budgetRows,
            'available_years' => $availableYears
        ]);
    }

    /**
     * Update/override GAD mandate budget allocation.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function updateOfficeBudget()
    {
        $db = \Config\Database::connect();
        $gpbId = $this->request->getPost('id');
        $field = $this->request->getPost('field');
        $newValue = (float) $this->request->getPost('new_value');

        if (!$gpbId || !$field) {
            return $this->fail('Invalid parameters');
        }

        if ($field === 'allocated') {
            $fiscalYear = (int)($this->request->getPost('fiscal_year') ?: date('Y'));
            $version = $db->tableExists('budget_versions')
                ? (new \App\Libraries\BudgetVersionService($db))->active($fiscalYear)
                : null;
            $gpbQuery = $db->table('gpb_items')->where('id', $gpbId);
            if ($version) {
                $gpbQuery->where('plan_version_id', $version['id']);
            } else {
                $gpbQuery->where('fiscal_year', $fiscalYear);
            }
            $gpb = $gpbQuery->get()->getRowArray();
            if (!$gpb) {
                return $this->fail('Mandate activity not found');
            }

            $db->table('gpb_items')
                ->where('id', $gpb['id'])
                ->update(['budget' => $newValue]);
        }

        return $this->respond([
            'success' => true,
            'message' => 'Mandate budget updated successfully'
        ]);
    }

    /**
     * Get list of GAD Plan activities as mandates with their remaining balances.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getAvailableMandates()
    {
        $db = \Config\Database::connect();
        $fiscalYear = (int)($this->request->getGet('fiscal_year') ?: date('Y'));
        $version = $db->tableExists('budget_versions')
            ? (new \App\Libraries\BudgetVersionService($db))->active($fiscalYear)
            : null;
        $gpbQuery = $db->table('gpb_items');
        if ($version) {
            $gpbQuery->where('plan_version_id', $version['id']);
        } else {
            $gpbQuery->where('fiscal_year', $fiscalYear);
        }
        $gpbs = $gpbQuery->get()->getResultArray();
        $mandates = [];

        foreach ($gpbs as $gpb) {
            $gpbId = $gpb['id'];
            $totalBudget = (float) $gpb['budget'];

            // Sum proposed budget of all active designs linked to this GPB activity
            $lineageIds = [$gpbId];
            if (!empty($gpb['item_key'])) {
                $lineageIds = array_column(
                    $db->table('gpb_items')->select('id')->where('item_key', $gpb['item_key'])->get()->getResultArray(),
                    'id'
                );
            }
            $proposedActive = $db->table('activity_design')
                ->selectSum('proposed_budget')
                ->whereIn('gpb_id', $lineageIds)
                ->get()->getRow()->proposed_budget ?? 0.0;

            $proposedArchived = $db->table('activity_design')
                ->selectSum('proposed_budget')
                ->whereIn('gpb_id', $lineageIds)
                ->where('is_archived', 1)
                ->get()->getRow()->proposed_budget ?? 0.0;

            $utilized = (float) $proposedActive + (float) $proposedArchived;
            $currentBalance = max(0.0, $totalBudget - $utilized);

            $mandates[] = [
                'id' => $gpbId,
                'control_no' => 'GPB-' . $gpbId,
                'title' => $gpb['activity'] ?: $gpb['mandate'],
                'current_balance' => $currentBalance
            ];
        }

        return $this->respond($mandates);
    }

    /**
     * Get recent budget realignment logs.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getRealignmentLogs()
    {
        $db = \Config\Database::connect();
        $logs = $db->table('budget_realignment_logs brl')
            ->select('brl.*, gpb.activity as mandate_title')
            ->join('gpb_items gpb', 'gpb.id = brl.gpb_id', 'left')
            ->orderBy('brl.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $formattedLogs = [];
        foreach ($logs as $log) {
            $formattedLogs[] = [
                'id' => $log['id'],
                'reference_no' => $log['reference_no'],
                'mandate_title' => $log['mandate_title'] ?: 'General Fund Pool',
                'type' => $log['type'],
                'amount' => (float) $log['amount'],
                'justification' => $log['justification'],
                'created_at' => date('M d, Y h:i A', strtotime($log['created_at']))
            ];
        }

        return $this->respond($formattedLogs);
    }

    /**
     * Get global GAD financial meta summary.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getFinancialMeta()
    {
        $db = \Config\Database::connect();
        $fiscalYear = (int)($this->request->getGet('fiscal_year') ?: date('Y'));
        $version = $db->tableExists('budget_versions')
            ? (new \App\Libraries\BudgetVersionService($db))->active($fiscalYear)
            : null;
        $budgetQuery = $db->table('gpb_items');
        if ($version) {
            $budgetQuery->where('plan_version_id', $version['id']);
        } else {
            $budgetQuery->where('fiscal_year', $fiscalYear);
        }
        $totalBudget = $budgetQuery
            ->selectSum('budget')
            ->get()->getRow()->budget ?? 0.0;

        // Sum proposed budget of all designs
        $totalUtilizedActive = $db->table('activity_design')
            ->selectSum('proposed_budget')
            ->get()->getRow()->proposed_budget ?? 0.0;

        $totalUtilizedArchived = $db->table('activity_design')
            ->selectSum('proposed_budget')
            ->where('is_archived', 1)
            ->get()->getRow()->proposed_budget ?? 0.0;

        $totalBudget = (float) $totalBudget;
        $totalUtilized = (float) $totalUtilizedActive + (float) $totalUtilizedArchived;
        $utilizationRate = $totalBudget > 0 ? round(($totalUtilized / $totalBudget) * 100, 1) : 0.0;

        return $this->respond([
            'totalBudget' => $totalBudget,
            'totalUtilized' => $totalUtilized,
            'utilizationRate' => $utilizationRate
        ]);
    }

    /**
     * Execute a budget realignment or augmentation transaction.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function executeRealignment()
    {
        $db = \Config\Database::connect();
        $gpbId = $this->request->getPost('mandate_id');
        $type = $this->request->getPost('type'); // 'augmentation' or 'realignment'
        $amount = (float) $this->request->getPost('amount');
        $justification = $this->request->getPost('justification');

        if (!$gpbId || !$type || !$amount || !$justification) {
            return $this->fail('All adjustment parameters are required.');
        }

        $gpb = $db->table('gpb_items')->where('id', $gpbId)->get()->getRowArray();
        if (!$gpb) {
            return $this->fail('Target mandate activity not found.');
        }

        try {
            $db->transStart();

            // Generate reference number
            $refNo = 'REF-' . strtoupper(bin2hex(random_bytes(3)));

            // 1. Record in logs
            $db->table('budget_realignment_logs')->insert([
                'reference_no' => $refNo,
                'gpb_id' => $gpbId,
                'type' => $type,
                'amount' => $amount,
                'justification' => $justification
            ]);

            // 2. Adjust target GPB activity budget
            $currentBudget = (float) $gpb['budget'];
            $newBudget = $currentBudget;

            if ($type === 'augmentation') {
                $newBudget += $amount;
            } else if ($type === 'realignment') {
                $newBudget -= $amount;
                if ($newBudget < 0) {
                    throw new \Exception('Realignment exceeds current available budget balance.');
                }
            }

            $db->table('gpb_items')
                ->where('id', $gpbId)
                ->update(['budget' => $newBudget]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Failed to process realignment transaction.');
            }

            return $this->respond([
                'success' => true,
                'message' => 'Financial adjustment committed successfully'
            ]);

        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Handle CORS preflight OPTIONS requests.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function optionsHandler()
    {
        return $this->respond(['status' => 200]);
    }
}
