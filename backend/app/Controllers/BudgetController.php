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
        
        $totalBudget = $db->table('gad_plan_budget')
            ->selectSum('gad_budget')
            ->get()->getRow()->gad_budget ?? 0.0;

        $archivedDesigns = $db->table('archived_activity_designs')
            ->where('status', 'Approved')
            ->get()
            ->getResultArray();

        $utilized = 0.0;
        $pendingApproved = 0.0;

        foreach ($archivedDesigns as $design) {
            $designId = $design['original_act_design_id'];

            // Check for completed/verified accomplishment report (active or archived)
            $report = $db->table('accomplishment_report')
                ->where('act_design_id', $designId)
                ->whereIn('status', ['Completed', 'Verified'])
                ->get()
                ->getRowArray();

            if (!$report) {
                $report = $db->table('archived_accomplishment_reports')
                    ->where('act_design_id', $designId)
                    ->whereIn('status', ['Completed', 'Verified'])
                    ->get()
                    ->getRowArray();
            }

            if ($report) {
                $reportId = $report['id'] ?? $report['original_report_id'];
                $isActiveReport = isset($report['id']) && empty($report['original_report_id']);
                $table = $isActiveReport ? 'accomplishment_budget_items' : 'archived_accomplishment_budget_items';

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
        
        $rows = $db->table('gad_plan_budget gpb')
            ->select('gpb.*, gpb.source_of_budget AS source')
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
     * Get real-time office budget utilization monitoring data.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getOfficeUtilization()
    {
        $db = \Config\Database::connect();
        $offices = $db->table('office_units')->orderBy('office_name', 'ASC')->get()->getResultArray();
        $budgetRows = [];

        foreach ($offices as $office) {
            $officeId = $office['office_id'];

            // 1. Calculate Allocated Budget (from GPB activities mapped to this office)
            $allocated = (float) $db->table('gad_plan_budget')
                ->selectSum('gad_budget')
                ->like('responsible_unit_office', $office['office_name'])
                ->get()->getRow()->gad_budget ?? 0.0;

            // 2. Calculate Utilized Budget (from activity designs and accomplishment reports)
            $users = $db->table('users')
                ->where('office_id', $officeId)
                ->get()
                ->getResultArray();
            $userIds = array_column($users, 'id');

            $utilized = 0.0;
            $pendingApproved = 0.0;
            if (!empty($userIds)) {
                // Get approved activity designs
                $archivedDesigns = $db->table('archived_activity_designs')
                    ->whereIn('user_id', $userIds)
                    ->where('status', 'Approved')
                    ->get()
                    ->getResultArray();

                foreach ($archivedDesigns as $design) {
                    $designId = $design['original_act_design_id'];

                    // Check for a completed accomplishment report (active or archived)
                    $report = $db->table('accomplishment_report')
                        ->where('act_design_id', $designId)
                        ->whereIn('status', ['Completed', 'Verified'])
                        ->get()
                        ->getRowArray();

                    if (!$report) {
                        $report = $db->table('archived_accomplishment_reports')
                            ->where('act_design_id', $designId)
                            ->whereIn('status', ['Completed', 'Verified'])
                            ->get()
                            ->getRowArray();
                    }

                    if ($report) {
                        // Use actual spending total from accomplishment_budget_items
                        $reportId = $report['id'] ?? $report['original_report_id'];
                        $isActiveReport = isset($report['id']) && empty($report['original_report_id']);
                        $table = $isActiveReport ? 'accomplishment_budget_items' : 'archived_accomplishment_budget_items';
                        
                        $actualTotalRow = $db->table($table)
                            ->select('SUM(amount) as total')
                            ->where('accomplishment_report_id', $reportId)
                            ->get()
                            ->getRowArray();

                        if ($actualTotalRow && $actualTotalRow['total'] !== null) {
                            $utilized += (float)$actualTotalRow['total'];
                        }
                    } else {
                        // No completed report, so it goes to pending approved
                        $pendingApproved += (float) $design['proposed_budget'];
                    }
                }
            }

            $remaining = max(0.0, $allocated - $utilized - $pendingApproved);
            $utilizationRate = $allocated > 0 ? ($utilized / $allocated) * 100 : 0.0;

            // Generate acronym from office name
            $words = explode(' ', $office['office_name']);
            $acronym = '';
            foreach ($words as $w) {
                if (ctype_upper($w[0] ?? '')) {
                    $acronym .= $w[0];
                }
            }
            if (empty($acronym)) {
                $acronym = substr($office['office_name'], 0, 3);
            }

            $budgetRows[] = [
                'id' => $officeId,
                'unit_name' => $office['office_name'],
                'unit_code' => strtoupper($acronym),
                'allocated' => $allocated,
                'utilized' => $utilized,
                'remaining' => $remaining,
                'utilizationRate' => $utilizationRate
            ];
        }

        return $this->respond($budgetRows);
    }

    /**
     * Update/override office budget allocation.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function updateOfficeBudget()
    {
        $db = \Config\Database::connect();
        $officeId = $this->request->getPost('id');
        $field = $this->request->getPost('field');
        $newValue = (float) $this->request->getPost('new_value');

        if (!$officeId || !$field) {
            return $this->fail('Invalid parameters');
        }

        $office = $db->table('office_units')->where('office_id', $officeId)->get()->getRowArray();
        if (!$office) {
            return $this->fail('Office not found');
        }

        if ($field === 'allocated') {
            $existingGpb = $db->table('gad_plan_budget')
                ->like('responsible_unit_office', $office['office_name'])
                ->get()
                ->getRowArray();

            if ($existingGpb) {
                // Update the first matched GPB activity budget directly
                $db->table('gad_plan_budget')
                    ->where('gpb_id', $existingGpb['gpb_id'])
                    ->update(['gad_budget' => $newValue]);
            } else {
                // Create a default GPB activity
                $db->table('gad_plan_budget')->insert([
                    'gender_issue_mandate' => 'General Budget Allocation',
                    'gad_activity' => 'General GAD budget allocation for ' . $office['office_name'],
                    'gad_budget' => $newValue,
                    'responsible_unit_office' => $office['office_name'],
                    'form_type' => 'client-focused activity'
                ]);
            }
        }

        return $this->respond([
            'success' => true,
            'message' => 'Office budget updated successfully'
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
        $gpbs = $db->table('gad_plan_budget')->get()->getResultArray();
        $mandates = [];

        foreach ($gpbs as $gpb) {
            $gpbId = $gpb['gpb_id'];
            $totalBudget = (float) $gpb['gad_budget'];

            // Sum proposed budget of all active designs linked to this GPB activity
            $proposedActive = $db->table('activity_design')
                ->selectSum('proposed_budget')
                ->where('gpb_id', $gpbId)
                ->get()->getRow()->proposed_budget ?? 0.0;

            $proposedArchived = $db->table('archived_activity_designs')
                ->selectSum('proposed_budget')
                ->where('gpb_id', $gpbId)
                ->get()->getRow()->proposed_budget ?? 0.0;

            $utilized = (float) $proposedActive + (float) $proposedArchived;
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
        
        $totalBudget = $db->table('gad_plan_budget')
            ->selectSum('gad_budget')
            ->get()->getRow()->gad_budget ?? 0.0;

        // Sum proposed budget of all designs
        $totalUtilizedActive = $db->table('activity_design')
            ->selectSum('proposed_budget')
            ->get()->getRow()->proposed_budget ?? 0.0;

        $totalUtilizedArchived = $db->table('archived_activity_designs')
            ->selectSum('proposed_budget')
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

            // 2. Adjust target GPB activity budget
            $currentBudget = (float) $gpb['gad_budget'];
            $newBudget = $currentBudget;

            if ($type === 'augmentation') {
                $newBudget += $amount;
            } else if ($type === 'realignment') {
                $newBudget -= $amount;
                if ($newBudget < 0) {
                    throw new \Exception('Realignment exceeds current available budget balance.');
                }
            }

            $db->table('gad_plan_budget')
                ->where('gpb_id', $gpbId)
                ->update(['gad_budget' => $newBudget]);

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
