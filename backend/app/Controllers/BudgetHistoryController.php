<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Libraries\JwtHelper;
use App\Libraries\BudgetVersionService;
use App\Models\SettingModel;

class BudgetHistoryController extends BaseController
{
    use ResponseTrait;

    private function allowed(): bool
    {
        $payload = $this->request->jwtPayload ?? [];
        $roles = [
            strtolower(trim((string)($payload['role'] ?? ''))),
            strtolower(trim((string)($payload['user_role'] ?? ''))),
        ];
        foreach ($roles as $role) {
            $normalized = str_replace(['-', '_'], ' ', $role);
            if (in_array($normalized, ['admin', 'director', 'staff', 'gad staff', 'superadmin'], true)) {
                return true;
            }
        }
        return false;
    }

    public function index()
    {
        if (!$this->allowed()) return $this->failForbidden('Only the Director or GAD Staff may view Budget History.');
        $db = \Config\Database::connect();
        
        // Single batch query for item counts to eliminate N+1 queries
        $countsByVersion = [];
        $countRows = $db->table('gpb_items')
            ->select('plan_version_id, COUNT(*) as cnt')
            ->where('plan_version_id IS NOT NULL')
            ->groupBy('plan_version_id')
            ->get()
            ->getResultArray();
        foreach ($countRows as $cr) {
            $countsByVersion[$cr['plan_version_id']] = (int)$cr['cnt'];
        }

        $rows = $db->table('budget_versions')
            ->orderBy('fiscal_year', 'DESC')
            ->orderBy('version_number', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['organization'] = json_decode($row['organization_data'] ?? '{}', true) ?: [];
            $row['item_count'] = $countsByVersion[$row['id']] ?? 0;
            unset($row['organization_data']);
        }
        return $this->respond($rows);
    }

    public function show(int $id)
    {
        if (!$this->allowed()) return $this->failForbidden('Only the Director or GAD Staff may view Budget History.');
        $db = \Config\Database::connect();
        $version = $db->table('budget_versions')->where('id', $id)->get()->getRowArray();
        if (!$version) return $this->failNotFound('Budget version not found.');
        
        $fiscalYear = (int)$version['fiscal_year'];

        // Merge organization settings so institutional details are not blank
        $orgData = [
            'name' => 'Benguet State University',
            'category' => 'State Universities and Colleges',
            'hierarchy' => 'Benguet State University',
            'year' => (string)$fiscalYear,
            'totalOrgBudget' => 1062488000.00,
            'preparedByName' => 'Jude Laoagan Tayaben, GAD Director',
            'approvedByName' => 'Kenneth Alip Laruan, President',
        ];
        if ($db->tableExists('settings')) {
            $settingModel = new SettingModel();
            $settings = $settingModel->getByFiscalYear($fiscalYear);
            if (!empty($settings)) {
                foreach ($orgData as $k => $v) {
                    if ($k !== 'otherSources' && isset($settings[$k])) {
                        $orgData[$k] = $settings[$k];
                    }
                }
            }
        }
        $versionOrg = json_decode($version['organization_data'] ?? '{}', true) ?: [];
        if (!empty($versionOrg)) {
            $orgData = array_merge($orgData, $versionOrg);
        }
        $version['organization'] = $orgData;

        $items = $db->table('gpb_items')->where('plan_version_id', $id)->orderBy('sort_order')->get()->getResultArray();
        $version['items'] = $items;
        $itemIds = array_column($items, 'id');
        
        $linkedDesigns = [];
        $linkedReports = [];
        if ($itemIds) {
            $designIds = array_column(
                $db->table('activity_design_mandates')
                    ->select('act_design_id')->whereIn('mandate_id', $itemIds)->get()->getResultArray(),
                'act_design_id'
            );
            $directDesignIds = array_column(
                $db->table('activity_design')->select('act_design_id')->whereIn('gpb_id', $itemIds)->get()->getResultArray(),
                'act_design_id'
            );
            $designIds = array_values(array_unique(array_merge($designIds, $directDesignIds)));
            if ($designIds) {
                $linkedDesigns = $db->table('activity_design')
                    ->select('act_design_id, control_number, activity_title, status, user_id, created_at, proposed_budget')
                    ->whereIn('act_design_id', $designIds)->get()->getResultArray();
                $controlNumbers = array_values(array_filter(array_column($linkedDesigns, 'control_number')));
                if ($controlNumbers) {
                    $linkedReports = $db->table('accomplishment_report')
                        ->select('id, control_number, activity_title, status, created_at')
                        ->whereIn('control_number', $controlNumbers)->get()->getResultArray();
                    
                    // Fetch verified actual expenditures for accomplishment reports
                    $reportIds = array_column($linkedReports, 'id');
                    $expendituresByReport = [];
                    if (!empty($reportIds)) {
                        $expRows = $db->table('budget_item_mandate_allocations bima')
                            ->select('abi.accomplishment_report_id, SUM(bima.allocated_amount) as total_expenditure')
                            ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
                            ->where('bima.item_type', 'AR')
                            ->whereIn('abi.accomplishment_report_id', $reportIds)
                            ->groupBy('abi.accomplishment_report_id')
                            ->get()
                            ->getResultArray();
                        foreach ($expRows as $er) {
                            $expendituresByReport[$er['accomplishment_report_id']] = (float)$er['total_expenditure'];
                        }
                    }

                    foreach ($linkedReports as &$report) {
                        $report['actual_expenditure'] = $expendituresByReport[$report['id']] ?? 0.0;
                    }
                    unset($report);
                }
            }
        }
        $version['linked_documents'] = [
            'activity_designs' => $linkedDesigns,
            'accomplishment_reports' => $linkedReports,
        ];

        // If the version is active (or has no saved snapshot), compute live monitoring state
        if ($version['status'] === 'active') {
            $versionService = new BudgetVersionService($db);
            $versionService->captureMonitoringSnapshot($id, $fiscalYear);
        }

        if ($db->tableExists('budget_version_monitoring_snapshots')) {
            $snapshot = $db->table('budget_version_monitoring_snapshots')
                ->where('plan_version_id', $id)->get()->getRowArray();
            if ($snapshot) {
                $allocated = (float)($snapshot['total_allocated'] ?? $snapshot['total_budget'] ?? 0);
                $utilized  = (float)($snapshot['total_utilized'] ?? 0);
                $pending   = (float)($snapshot['total_pending'] ?? $snapshot['total_pending_approved'] ?? 0);
                $remaining = (float)($snapshot['total_remaining'] ?? $snapshot['remaining_balance'] ?? 0);
                $rate      = (float)($snapshot['total_utilization_rate'] ?? $snapshot['utilization_rate'] ?? 0);

                $snapshot['total_allocated']        = $allocated;
                $snapshot['total_budget']           = $allocated;
                $snapshot['total_utilized']         = $utilized;
                $snapshot['total_pending']          = $pending;
                $snapshot['total_pending_approved'] = $pending;
                $snapshot['total_remaining']        = $remaining;
                $snapshot['remaining_balance']      = $remaining;
                $snapshot['utilization_rate']       = $rate;

                $snapshot['rows'] = json_decode($snapshot['rows_data'] ?? '[]', true) ?: [];
                unset($snapshot['rows_data']);
                $version['monitoring_snapshot'] = $snapshot;
            }
        }
        unset($version['organization_data']);
        return $this->respond($version);
    }

    /**
     * Export complete historical snapshot to a ZIP file containing 3 self-contained styled HTML documents
     */
    public function exportHtml(int $id)
    {
        if (!$this->allowed()) return $this->failForbidden('Only the Director or GAD Staff may export budget versions.');
        
        if (!extension_loaded('zip')) {
            return $this->failServerError('The PHP zip extension is required but not installed or enabled.');
        }

        $db = \Config\Database::connect();
        $version = $db->table('budget_versions')->where('id', $id)->get()->getRowArray();
        if (!$version) return $this->failNotFound('Budget version not found.');

        $fiscalYear = (int)$version['fiscal_year'];
        $versionNumber = (int)$version['version_number'];

        // If exporting an active version that doesn't have a snapshot captured yet, capture current live state
        if ($version['status'] === 'active') {
            $versionService = new BudgetVersionService($db);
            $versionService->captureMonitoringSnapshot($id, $fiscalYear);
        }

        // Merge organization settings
        $orgData = [
            'name' => 'Benguet State University',
            'category' => 'State Universities and Colleges',
            'hierarchy' => 'Benguet State University',
            'year' => (string)$fiscalYear,
            'totalOrgBudget' => 1062488000.00,
            'preparedByName' => 'Jude Laoagan Tayaben, GAD Director',
            'approvedByName' => 'Kenneth Alip Laruan, President',
        ];
        if ($db->tableExists('settings')) {
            $settingModel = new SettingModel();
            $settings = $settingModel->getByFiscalYear($fiscalYear);
            if (!empty($settings)) {
                foreach ($orgData as $k => $v) {
                    if ($k !== 'otherSources' && isset($settings[$k])) {
                        $orgData[$k] = $settings[$k];
                    }
                }
            }
        }
        $versionOrg = json_decode($version['organization_data'] ?? '{}', true) ?: [];
        if (!empty($versionOrg)) {
            $orgData = array_merge($orgData, $versionOrg);
        }

        $items = $db->table('gpb_items')->where('plan_version_id', $id)->orderBy('sort_order')->get()->getResultArray();

        // Monitoring snapshot
        $snapshotRows = [];
        $snapshotMeta = [];
        if ($db->tableExists('budget_version_monitoring_snapshots')) {
            $snapshot = $db->table('budget_version_monitoring_snapshots')
                ->where('plan_version_id', $id)->get()->getRowArray();
            if ($snapshot) {
                $snapshotRows = json_decode($snapshot['rows_data'] ?? '[]', true) ?: [];
                $snapshotMeta = $snapshot;
                $snapshotMeta['total_allocated'] = (float)($snapshot['total_allocated'] ?? $snapshot['total_budget'] ?? 0);
                $snapshotMeta['total_budget']    = $snapshotMeta['total_allocated'];
                $snapshotMeta['total_utilized']  = (float)($snapshot['total_utilized'] ?? 0);
                $snapshotMeta['total_pending']   = (float)($snapshot['total_pending'] ?? $snapshot['total_pending_approved'] ?? 0);
                $snapshotMeta['total_remaining'] = (float)($snapshot['total_remaining'] ?? $snapshot['remaining_balance'] ?? 0);
            }
        }

        // Linked documents
        $itemIds = array_column($items, 'id');
        $linkedDesigns = [];
        $linkedReports = [];
        if ($itemIds) {
            $designIds = array_column(
                $db->table('activity_design_mandates')
                    ->select('act_design_id')->whereIn('mandate_id', $itemIds)->get()->getResultArray(),
                'act_design_id'
            );
            $directDesignIds = array_column(
                $db->table('activity_design')->select('act_design_id')->whereIn('gpb_id', $itemIds)->get()->getResultArray(),
                'act_design_id'
            );
            $designIds = array_values(array_unique(array_merge($designIds, $directDesignIds)));
            if ($designIds) {
                $linkedDesigns = $db->table('activity_design')
                    ->select('act_design_id, control_number, activity_title, status, user_id, created_at, proposed_budget')
                    ->whereIn('act_design_id', $designIds)->get()->getResultArray();
                $controlNumbers = array_values(array_filter(array_column($linkedDesigns, 'control_number')));
                if ($controlNumbers) {
                    $linkedReports = $db->table('accomplishment_report')
                        ->select('id, control_number, activity_title, status, created_at')
                        ->whereIn('control_number', $controlNumbers)->get()->getResultArray();
                    
                    $reportIds = array_column($linkedReports, 'id');
                    $expendituresByReport = [];
                    if (!empty($reportIds)) {
                        $expRows = $db->table('budget_item_mandate_allocations bima')
                            ->select('abi.accomplishment_report_id, SUM(bima.allocated_amount) as total_expenditure')
                            ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
                            ->where('bima.item_type', 'AR')
                            ->whereIn('abi.accomplishment_report_id', $reportIds)
                            ->groupBy('abi.accomplishment_report_id')
                            ->get()
                            ->getResultArray();
                        foreach ($expRows as $er) {
                            $expendituresByReport[$er['accomplishment_report_id']] = (float)$er['total_expenditure'];
                        }
                    }

                    foreach ($linkedReports as &$report) {
                        $report['actual_expenditure'] = $expendituresByReport[$report['id']] ?? 0.0;
                    }
                    unset($report);
                }
            }
        }

        // Export metadata & standardized footer
        $dt = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
        $formattedDate = $dt->format('F d, Y h:i A T');
        $payload = $this->request->jwtPayload ?? [];
        $exportedBy = trim(($payload['name'] ?? '') ?: ($payload['email'] ?? 'System User'));
        if (empty($exportedBy)) {
            $exportedBy = 'Authenticated User (ID: ' . JwtHelper::getAuthUserId($this->request) . ')';
        }

        $footerHtml = '
            <div class="footer">
                <hr style="border:0;border-top:1px solid #e2e8f0;margin:24px 0 12px 0;">
                <p style="font-size:11px;color:#718096;margin:0;">
                    Exported from GAD AMS &bull; Fiscal Year: ' . $fiscalYear . ' (Version ' . $versionNumber . ') &bull; 
                    Status: ' . htmlspecialchars(ucfirst($version['status'] ?? 'closed'), ENT_QUOTES, 'UTF-8') . ' &bull; 
                    Exported by: ' . htmlspecialchars($exportedBy, ENT_QUOTES, 'UTF-8') . ' &bull; 
                    Timestamp: ' . htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') . '
                </p>
                <p style="font-size:10px;color:#a0aec0;margin-top:4px;">
                    Retained archive copy. Live operational records reflect state as of the export timestamp.
                </p>
            </div>
        ';

        $baseCss = '
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 30px; color: #2d3748; background-color: #fff; line-height: 1.5; }
                h1 { color: #1a202c; font-size: 22px; margin-bottom: 6px; }
                h2 { color: #4a5568; font-size: 16px; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                .badge { display: inline-block; padding: 3px 8px; font-size: 11px; font-weight: 600; border-radius: 4px; text-transform: uppercase; }
                .badge-closed { background-color: #edf2f7; color: #4a5568; }
                .badge-active { background-color: #c6f6d5; color: #22543d; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; font-size: 12px; }
                th, td { border: 1px solid #cbd5e0; padding: 8px 10px; text-align: left; }
                th { background-color: #f7fafc; color: #4a5568; font-weight: 600; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .meta-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; }
                .meta-box p { margin: 4px 0; }
                .amount { font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace; }
            </style>
        ';

        // 1. Plan and Budget Page HTML
        $html1 = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Plan and Budget - FY' . $fiscalYear . ' v' . $versionNumber . '</title>' . $baseCss . '</head><body>';
        $html1 .= '<h1>Gender and Development Plan and Budget (GPB)</h1>';
        $html1 .= '<h2>Fiscal Year ' . $fiscalYear . ' &mdash; Version ' . $versionNumber . ' <span class="badge ' . ($version['status'] === 'active' ? 'badge-active' : 'badge-closed') . '">' . htmlspecialchars(strtoupper($version['status'] ?? 'CLOSED'), ENT_QUOTES, 'UTF-8') . '</span></h2>';
        $html1 .= '<div class="meta-box">';
        $html1 .= '<p><strong>Organization:</strong> ' . htmlspecialchars($orgData['name'] ?? 'Benguet State University', ENT_QUOTES, 'UTF-8') . '</p>';
        $html1 .= '<p><strong>Category / Hierarchy:</strong> ' . htmlspecialchars($orgData['category'] ?? 'State Universities and Colleges', ENT_QUOTES, 'UTF-8') . ' / ' . htmlspecialchars($orgData['hierarchy'] ?? 'Benguet State University', ENT_QUOTES, 'UTF-8') . '</p>';
        $html1 .= '<p><strong>Total Institutional Budget:</strong> ₱' . number_format((float)($orgData['totalOrgBudget'] ?? 0), 2) . '</p>';
        $html1 .= '</div>';
        $html1 .= '<table><thead><tr>';
        $html1 .= '<th>#</th><th>Section</th><th>Gender Issue / Mandate</th><th>Cause of Issue</th><th>GAD Objective</th><th>Relevant MFO/PAP</th><th>GAD Activity</th><th>Performance Target</th><th>Approved Budget</th>';
        $html1 .= '</tr></thead><tbody>';
        if (empty($items)) {
            $html1 .= '<tr><td colspan="9" class="text-center" style="padding:20px;color:#a0aec0;">No GPB items recorded for this version.</td></tr>';
        } else {
            $totalBudget = 0.0;
            foreach ($items as $idx => $it) {
                // Column name fallbacks for backward compatibility
                $itemBudget = (float)($it['budget'] ?? $it['total_budget'] ?? $it['raw_budget'] ?? 0);
                $objective = $it['objective'] ?? $it['result'] ?? $it['gad_objective'] ?? '';
                $relevantOrg = $it['ppa'] ?? $it['mfo'] ?? $it['relevant_org'] ?? '';
                $activity = $it['activity'] ?? $it['gad_activity'] ?? '';
                $target = $it['targets'] ?? $it['indicators'] ?? $it['target'] ?? '';

                $totalBudget += $itemBudget;
                $html1 .= '<tr>';
                $html1 .= '<td class="text-center">' . ($idx + 1) . '</td>';
                $html1 .= '<td>' . htmlspecialchars($it['section'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                $html1 .= '<td>' . nl2br(htmlspecialchars($it['mandate'] ?? '', ENT_QUOTES, 'UTF-8')) . '</td>';
                $html1 .= '<td>' . nl2br(htmlspecialchars($it['cause'] ?? '', ENT_QUOTES, 'UTF-8')) . '</td>';
                $html1 .= '<td>' . nl2br(htmlspecialchars($objective, ENT_QUOTES, 'UTF-8')) . '</td>';
                $html1 .= '<td>' . nl2br(htmlspecialchars($relevantOrg, ENT_QUOTES, 'UTF-8')) . '</td>';
                $html1 .= '<td>' . nl2br(htmlspecialchars($activity, ENT_QUOTES, 'UTF-8')) . '</td>';
                $html1 .= '<td>' . nl2br(htmlspecialchars($target, ENT_QUOTES, 'UTF-8')) . '</td>';
                $html1 .= '<td class="text-right amount">₱' . number_format($itemBudget, 2) . '</td>';
                $html1 .= '</tr>';
            }
            $html1 .= '<tr style="font-weight:bold;background-color:#edf2f7;"><td colspan="8" class="text-right">TOTAL GAD BUDGET</td><td class="text-right amount">₱' . number_format($totalBudget, 2) . '</td></tr>';
        }
        $html1 .= '</tbody></table>';
        $html1 .= $footerHtml . '</body></html>';

        // 2. Financial Monitoring Snapshot Page HTML
        $html2 = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Financial Monitoring Snapshot - FY' . $fiscalYear . ' v' . $versionNumber . '</title>' . $baseCss . '</head><body>';
        $html2 .= '<h1>Financial Monitoring Snapshot</h1>';
        $html2 .= '<h2>Archived Financial State at Version Close &mdash; FY' . $fiscalYear . ' v' . $versionNumber . '</h2>';
        $html2 .= '<div class="meta-box">';
        if (!empty($snapshotMeta['captured_at'])) {
            $html2 .= '<p><strong>Snapshot Captured At:</strong> ' . htmlspecialchars($snapshotMeta['captured_at'], ENT_QUOTES, 'UTF-8') . '</p>';
        }
        $html2 .= '<p><strong>Total Allocated:</strong> ₱' . number_format((float)($snapshotMeta['total_allocated'] ?? $snapshotMeta['total_budget'] ?? 0), 2) . ' &bull; <strong>Total Utilized:</strong> ₱' . number_format((float)($snapshotMeta['total_utilized'] ?? 0), 2) . ' &bull; <strong>Overall Remaining:</strong> ₱' . number_format((float)($snapshotMeta['total_remaining'] ?? $snapshotMeta['remaining_balance'] ?? 0), 2) . '</p>';
        $html2 .= '</div>';
        $html2 .= '<table><thead><tr>';
        $html2 .= '<th>#</th><th>Section</th><th>GAD Activity</th><th>Allocated Budget</th><th>Utilized Amount</th><th>Pending Amount</th><th>Remaining Balance</th><th>Utilization %</th>';
        $html2 .= '</tr></thead><tbody>';
        if (empty($snapshotRows)) {
            $html2 .= '<tr><td colspan="8" class="text-center" style="padding:20px;color:#a0aec0;">Snapshot unavailable for this version (closed prior to snapshot recording).</td></tr>';
        } else {
            foreach ($snapshotRows as $idx => $sr) {
                $html2 .= '<tr>';
                $html2 .= '<td class="text-center">' . ($idx + 1) . '</td>';
                $html2 .= '<td>' . htmlspecialchars($sr['section'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                $html2 .= '<td>' . htmlspecialchars($sr['activity'] ?? ($sr['title'] ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
                $html2 .= '<td class="text-right amount">₱' . number_format((float)($sr['allocated'] ?? 0), 2) . '</td>';
                $html2 .= '<td class="text-right amount">₱' . number_format((float)($sr['utilized'] ?? 0), 2) . '</td>';
                $html2 .= '<td class="text-right amount">₱' . number_format((float)($sr['pending'] ?? 0), 2) . '</td>';
                $html2 .= '<td class="text-right amount">₱' . number_format((float)($sr['remaining'] ?? 0), 2) . '</td>';
                $html2 .= '<td class="text-right">' . number_format((float)($sr['utilization_rate'] ?? 0), 1) . '%</td>';
                $html2 .= '</tr>';
            }
        }
        $html2 .= '</tbody></table>';
        $html2 .= $footerHtml . '</body></html>';

        // 3. Linked Documents Page HTML
        $html3 = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Linked Documents - FY' . $fiscalYear . ' v' . $versionNumber . '</title>' . $baseCss . '</head><body>';
        $html3 .= '<h1>Linked Operational Documents</h1>';
        $html3 .= '<h2>Activity Designs &amp; Accomplishment Reports &mdash; (Status as of export date: ' . htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') . ')</h2>';
        
        $html3 .= '<h3 style="color:#2d3748;margin-top:24px;">Linked Activity Designs</h3>';
        $html3 .= '<table><thead><tr><th>Control #</th><th>Activity Title</th><th>Status</th><th>Proposed Budget</th><th>Date Created</th></tr></thead><tbody>';
        if (empty($linkedDesigns)) {
            $html3 .= '<tr><td colspan="5" class="text-center" style="padding:15px;color:#a0aec0;">No linked activity designs found.</td></tr>';
        } else {
            foreach ($linkedDesigns as $ad) {
                $html3 .= '<tr>';
                $html3 .= '<td><strong>' . htmlspecialchars($ad['control_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</strong></td>';
                $html3 .= '<td>' . htmlspecialchars($ad['activity_title'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                $html3 .= '<td><span class="badge" style="background:#e2e8f0;">' . htmlspecialchars($ad['status'] ?? '', ENT_QUOTES, 'UTF-8') . '</span></td>';
                $html3 .= '<td class="text-right amount">₱' . number_format((float)($ad['proposed_budget'] ?? 0), 2) . '</td>';
                $html3 .= '<td>' . htmlspecialchars($ad['created_at'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                $html3 .= '</tr>';
            }
        }
        $html3 .= '</tbody></table>';

        $html3 .= '<h3 style="color:#2d3748;margin-top:30px;">Linked Accomplishment Reports</h3>';
        $html3 .= '<table><thead><tr><th>Control #</th><th>Activity Title</th><th>Status</th><th>Verified Expenditure</th><th>Date Created</th></tr></thead><tbody>';
        if (empty($linkedReports)) {
            $html3 .= '<tr><td colspan="5" class="text-center" style="padding:15px;color:#a0aec0;">No linked accomplishment reports found.</td></tr>';
        } else {
            foreach ($linkedReports as $ar) {
                $html3 .= '<tr>';
                $html3 .= '<td><strong>' . htmlspecialchars($ar['control_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</strong></td>';
                $html3 .= '<td>' . htmlspecialchars($ar['activity_title'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                $html3 .= '<td><span class="badge" style="background:#e2e8f0;">' . htmlspecialchars($ar['status'] ?? '', ENT_QUOTES, 'UTF-8') . '</span></td>';
                $html3 .= '<td class="text-right amount">₱' . number_format((float)($ar['actual_expenditure'] ?? 0), 2) . '</td>';
                $html3 .= '<td>' . htmlspecialchars($ar['created_at'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                $html3 .= '</tr>';
            }
        }
        $html3 .= '</tbody></table>';
        $html3 .= $footerHtml . '</body></html>';

        // Package into ZIP
        $zipFile = tempnam(sys_get_temp_dir(), 'gad_zip_');
        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return $this->failServerError('Could not create temporary export archive.');
        }

        $zip->addFromString("1_Plan_and_Budget_FY{$fiscalYear}_v{$versionNumber}.html", $html1);
        $zip->addFromString("2_Financial_Monitoring_Snapshot_FY{$fiscalYear}_v{$versionNumber}.html", $html2);
        $zip->addFromString("3_Linked_Documents_FY{$fiscalYear}_v{$versionNumber}.html", $html3);
        $zip->close();

        // Read into memory to prevent premature unlinking during streaming
        $zipBinary = file_get_contents($zipFile);
        if (file_exists($zipFile)) {
            unlink($zipFile);
        }

        $downloadFilename = "Budget_History_FY{$fiscalYear}_v{$versionNumber}.zip";

        return $this->response
            ->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $downloadFilename . '"')
            ->setHeader('Content-Length', (string)strlen($zipBinary))
            ->setBody($zipBinary);
    }
}
