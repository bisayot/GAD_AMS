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
        
        $totalBudgetSum = $db->table('gpb_budget_breakdown')->selectSum('amount')->get()->getRow()->amount ?? 0.0;
        $totalBudget = (float) $totalBudgetSum;

        $settingModel = new \App\Models\SettingModel();
        $latestItem = $db->table('gpb_items')->select('fiscal_year')->orderBy('id', 'DESC')->get()->getRowArray();
        $latestYear = $latestItem ? $latestItem['fiscal_year'] : date('Y');
        $settings = $settingModel->getByFiscalYear($latestYear);
        $otherSources = isset($settings['otherSources']) ? (float) $settings['otherSources'] : 0.0;
        
        $totalBudget += $otherSources;

        // Fetch control numbers of completed/verified accomplishment reports
        $completedControlNumbers = array_column(
            $db->table('accomplishment_report')
                ->select('control_number')
                ->whereIn('status', ['Verified', 'Completed', 'Approved'])
                ->where('deleted_at', null)
                ->get()
                ->getResultArray(),
            'control_number'
        );
        $completedControlSet = array_flip($completedControlNumbers);

        // Fetch all approved activity designs
        $archivedDesigns = $db->table('activity_design')
            ->select('act_design_id, control_number')
            ->where('status', 'Approved')
            ->where('is_archived', 1)
            ->where('deleted_at', null)
            ->get()
            ->getResultArray();

        // Fetch sum of accomplishment budget items for each accomplishment report ID
        $utilizedSums = $db->table('accomplishment_budget_items abi')
            ->select('ar.control_number, SUM(abi.amount) as total')
            ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
            ->whereIn('ar.status', ['Verified', 'Completed', 'Approved'])
            ->where('ar.deleted_at', null)
            ->groupBy('ar.control_number')
            ->get()
            ->getResultArray();
        
        $utilizedMap = [];
        foreach ($utilizedSums as $row) {
            $utilizedMap[$row['control_number']] = (float)$row['total'];
        }

        // Fetch sum of activity budget items for all act_design_id
        $adSums = $db->table('activity_budget_items')
            ->select('act_design_id, SUM(amount) as total')
            ->groupBy('act_design_id')
            ->get()
            ->getResultArray();

        $adMap = [];
        foreach ($adSums as $row) {
            $adMap[$row['act_design_id']] = (float)$row['total'];
        }

        $utilized = 0.0;
        $pendingApproved = 0.0;

        foreach ($archivedDesigns as $design) {
            $ctrl = $design['control_number'];
            $designId = $design['act_design_id'];

            if (isset($completedControlSet[$ctrl])) {
                $utilized += $utilizedMap[$ctrl] ?? 0.0;
            } else {
                $pendingApproved += $adMap[$designId] ?? 0.0;
            }
        }

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
        
        $rows = $db->table('gad_plan_budget gpb')
            ->select('gpb.*, gpb.source_of_budget AS source')
            ->get()
            ->getResultArray();

        $breakdowns = $db->table('gpb_budget_breakdown')
            ->select('gpb_id, SUM(amount) as total_budget')
            ->groupBy('gpb_id')
            ->get()
            ->getResultArray();
            
        $totals = [];
        foreach ($breakdowns as $b) {
            $totals[$b['gpb_id']] = (float)$b['total_budget'];
        }

        foreach ($rows as &$row) {
            $row['gad_budget'] = $totals[$row['gpb_id']] ?? 0.0;
        }

        return $this->respond([
            'success' => true,
            'data'    => $rows
        ]);
    }

    /**
     * Get real-time office budget utilization monitoring data.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getOfficeUtilization()
    {
        $db = \Config\Database::connect();
        
        // 1. Fetch GPBs
        $gpbs = $db->table('gad_plan_budget')->orderBy('gpb_id', 'ASC')->get()->getResultArray();
        
        // 2. Fetch breakdowns
        $bLines = $db->table('gpb_budget_breakdown')->get()->getResultArray();
        $breakdownByGpb = [];
        foreach ($bLines as $bl) {
            $breakdownByGpb[$bl['gpb_id']][] = $bl;
        }
        
        // 3. Get completed/verified accomplishment reports control numbers
        $completedControlNumbers = array_column(
            $db->table('accomplishment_report')
                ->select('control_number')
                ->whereIn('status', ['Verified', 'Completed', 'Approved'])
                ->where('deleted_at', null)
                ->get()
                ->getResultArray(),
            'control_number'
        );
        $completedControlSet = array_flip($completedControlNumbers);
        
        // 4. Get utilized amounts grouped by gpb_id and line_id
        $utilizedByLine = $db->table('accomplishment_budget_items abi')
            ->select('abi.gpb_id, abi.gpb_budget_line_id, SUM(abi.allocated_amount) as total_utilized')
            ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
            ->whereIn('ar.status', ['Verified', 'Completed', 'Approved'])
            ->where('ar.deleted_at', null)
            ->groupBy('abi.gpb_id, abi.gpb_budget_line_id')
            ->get()
            ->getResultArray();
            
        $utilizedMap = [];
        foreach ($utilizedByLine as $row) {
            $utilizedMap[$row['gpb_id']][$row['gpb_budget_line_id']] = (float)$row['total_utilized'];
        }
        
        // 5. Get approved activity design allocations
        $adAllocations = $db->table('activity_budget_items abi')
            ->select('abi.gpb_id, abi.gpb_budget_line_id, abi.allocated_amount, ad.control_number')
            ->join('activity_design ad', 'ad.act_design_id = abi.act_design_id')
            ->where('ad.status', 'Approved')
            ->where('ad.deleted_at', null)
            ->get()
            ->getResultArray();
            
        $pendingMap = [];
        foreach ($adAllocations as $alloc) {
            if (!isset($completedControlSet[$alloc['control_number']])) {
                $gpbId = $alloc['gpb_id'];
                $lineId = $alloc['gpb_budget_line_id'];
                if (!isset($pendingMap[$gpbId][$lineId])) {
                    $pendingMap[$gpbId][$lineId] = 0.0;
                }
                $pendingMap[$gpbId][$lineId] += (float)$alloc['allocated_amount'];
            }
        }
        
        $budgetRows = [];
        foreach ($gpbs as $gpb) {
            $gpbId = $gpb['gpb_id'];
            
            $allocatedTotal = 0.0;
            $utilizedTotal = 0.0;
            $pendingTotal = 0.0;
            
            $breakdownRows = [];
            $lines = $breakdownByGpb[$gpbId] ?? [];
            
            foreach ($lines as $bl) {
                $lineId = $bl['line_id'];
                $blAllocated = (float)$bl['amount'];
                $blUtilized = $utilizedMap[$gpbId][$lineId] ?? 0.0;
                $blPending = $pendingMap[$gpbId][$lineId] ?? 0.0;
                $blRemaining = max(0.0, $blAllocated - $blUtilized - $blPending);
                
                $allocatedTotal += $blAllocated;
                $utilizedTotal += $blUtilized;
                $pendingTotal += $blPending;
                
                $breakdownRows[] = [
                    'line_id' => $lineId,
                    'category' => $bl['category'],
                    'source' => $bl['source'],
                    'allocated' => $blAllocated,
                    'utilized' => $blUtilized,
                    'pending' => $blPending,
                    'remaining' => $blRemaining
                ];
            }
            
            $remainingTotal = max(0.0, $allocatedTotal - $utilizedTotal - $pendingTotal);
            $utilizationRate = $allocatedTotal > 0 ? ($utilizedTotal / $allocatedTotal) * 100 : 0.0;
            
            $budgetRows[] = [
                'id' => $gpbId,
                'unit_name' => $gpb['gad_activity'] ?: $gpb['gender_issue_mandate'],
                'unit_code' => 'GPB-' . $gpbId,
                'allocated' => $allocatedTotal,
                'utilized' => $utilizedTotal,
                'pending_approved' => $pendingTotal,
                'remaining' => $remainingTotal,
                'utilizationRate' => $utilizationRate,
                'breakdown' => $breakdownRows
            ];
        }

        return $this->respond($budgetRows);
    }

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
            $gpb = $db->table('gad_plan_budget')->where('gpb_id', $gpbId)->get()->getRowArray();
            if (!$gpb) {
                return $this->fail('Mandate activity not found');
            }

            // Adjust breakdowns in gpb_budget_breakdown table
            $breakdowns = $db->table('gpb_budget_breakdown')
                ->where('gpb_id', $gpbId)
                ->get()
                ->getResultArray();

            if (count($breakdowns) === 1) {
                $db->table('gpb_budget_breakdown')
                    ->where('breakdown_id', $breakdowns[0]['breakdown_id'])
                    ->update(['amount' => $newValue]);
            } else if (count($breakdowns) > 1) {
                $othersSum = 0.0;
                for ($i = 1; $i < count($breakdowns); $i++) {
                    $othersSum += (float)$breakdowns[$i]['amount'];
                }
                $db->table('gpb_budget_breakdown')
                    ->where('breakdown_id', $breakdowns[0]['breakdown_id'])
                    ->update(['amount' => $newValue - $othersSum]);
            } else {
                $db->table('gpb_budget_breakdown')->insert([
                    'gpb_id' => $gpbId,
                    'line_id' => 'l-' . $gpbId,
                    'category' => 'Default Allocation',
                    'amount' => $newValue,
                    'source' => 'GAA'
                ]);
            }
        }

        return $this->respond([
            'success' => true,
            'message' => 'Mandate budget updated successfully'
        ]);
    }

    public function getAvailableMandates()
    {
        $db = \Config\Database::connect();
        $gpbs = $db->table('gad_plan_budget')->get()->getResultArray();
        
        $breakdowns = $db->table('gpb_budget_breakdown')
            ->select('gpb_id, SUM(amount) as total_budget')
            ->groupBy('gpb_id')
            ->get()
            ->getResultArray();
            
        $totals = [];
        foreach ($breakdowns as $b) {
            $totals[$b['gpb_id']] = (float)$b['total_budget'];
        }

        // Fetch sum of all approved activity designs grouped by gpb_id in a single query
        $utilizedSums = $db->table('activity_budget_items abi')
            ->select('abi.gpb_id, SUM(abi.amount) as total')
            ->join('activity_design ad', 'ad.act_design_id = abi.act_design_id')
            ->where('ad.status', 'Approved')
            ->where('ad.deleted_at', null)
            ->groupBy('abi.gpb_id')
            ->get()
            ->getResultArray();

        $utilizedMap = [];
        foreach ($utilizedSums as $row) {
            $utilizedMap[$row['gpb_id']] = (float)$row['total'];
        }

        $mandates = [];

        foreach ($gpbs as $gpb) {
            $gpbId = $gpb['gpb_id'];
            $totalBudget = $totals[$gpbId] ?? 0.0;
            $utilized = $utilizedMap[$gpbId] ?? 0.0;
            $currentBalance = max(0.0, $totalBudget - $utilized);

            $mandates[] = [
                'id' => $gpbId,
                'control_no' => 'GPB-' . $gpbId,
                'title' => $gpb['gad_activity'] ?: $gpb['gender_issue_mandate'],
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
            ->select('brl.*, gpb.gad_activity as mandate_title')
            ->join('gad_plan_budget gpb', 'gpb.gpb_id = brl.gpb_id', 'left')
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
        
        $totalBudgetSum = $db->table('gpb_budget_breakdown')
            ->selectSum('amount')
            ->get()->getRow()->amount ?? 0.0;
        $totalBudget = (float) $totalBudgetSum;

        // Sum amount of all approved activity designs
        $totalUtilized = $db->table('activity_budget_items abi')
            ->selectSum('abi.amount')
            ->join('activity_design ad', 'ad.act_design_id = abi.act_design_id')
            ->where('ad.status', 'Approved')
            ->where('ad.deleted_at', null)
            ->get()->getRow()->amount ?? 0.0;

        $totalBudget = (float) $totalBudget;
        $totalUtilized = (float) $totalUtilized;
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

        $gpb = $db->table('gad_plan_budget')->where('gpb_id', $gpbId)->get()->getRowArray();
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

            // 2. Adjust target GPB activity budget breakdown
            $breakdowns = $db->table('gpb_budget_breakdown')
                ->where('gpb_id', $gpbId)
                ->get()
                ->getResultArray();

            $currentBudget = 0.0;
            foreach ($breakdowns as $b) {
                $currentBudget += (float)$b['amount'];
            }

            $newBudget = $currentBudget;

            if ($type === 'augmentation') {
                $newBudget += $amount;
            } else if ($type === 'realignment') {
                $newBudget -= $amount;
                if ($newBudget < 0) {
                    throw new \Exception('Realignment exceeds current available budget balance.');
                }
            }

            if (count($breakdowns) === 1) {
                $db->table('gpb_budget_breakdown')
                    ->where('breakdown_id', $breakdowns[0]['breakdown_id'])
                    ->update(['amount' => $newBudget]);
            } else if (count($breakdowns) > 1) {
                $othersSum = 0.0;
                for ($i = 1; $i < count($breakdowns); $i++) {
                    $othersSum += (float)$breakdowns[$i]['amount'];
                }
                $db->table('gpb_budget_breakdown')
                    ->where('breakdown_id', $breakdowns[0]['breakdown_id'])
                    ->update(['amount' => $newBudget - $othersSum]);
            } else {
                $db->table('gpb_budget_breakdown')->insert([
                    'gpb_id' => $gpbId,
                    'line_id' => 'l-' . $gpbId,
                    'category' => 'Default Allocation',
                    'amount' => $newBudget,
                    'source' => 'GAA'
                ]);
            }

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
