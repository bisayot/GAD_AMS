<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

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
        $rows = $db->table('budget_versions')->orderBy('fiscal_year', 'DESC')->orderBy('version_number', 'DESC')->get()->getResultArray();
        foreach ($rows as &$row) {
            $row['organization'] = json_decode($row['organization_data'] ?? '{}', true) ?: [];
            $row['item_count'] = $db->table('gpb_items')->where('plan_version_id', $row['id'])->countAllResults();
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
        $version['organization'] = json_decode($version['organization_data'] ?? '{}', true) ?: [];
        $version['items'] = $db->table('gpb_items')->where('plan_version_id', $id)->orderBy('sort_order')->get()->getResultArray();
        $itemIds = array_column($version['items'], 'id');
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
                    foreach ($linkedReports as &$report) {
                        $report['proposed_budget'] = null;
                    }
                    unset($report);
                }
            }
        }
        $version['linked_documents'] = [
            'activity_designs' => $linkedDesigns,
            'accomplishment_reports' => $linkedReports,
        ];
        if ($db->tableExists('budget_version_monitoring_snapshots')) {
            $snapshot = $db->table('budget_version_monitoring_snapshots')
                ->where('plan_version_id', $id)->get()->getRowArray();
            if ($snapshot) {
                $snapshot['rows'] = json_decode($snapshot['rows_data'] ?? '[]', true) ?: [];
                unset($snapshot['rows_data']);
                $version['monitoring_snapshot'] = $snapshot;
            }
        }
        unset($version['organization_data']);
        return $this->respond($version);
    }

    public function restore(int $id)
    {
        if (!$this->allowed()) return $this->failForbidden('Only the Director or GAD Staff may restore a budget version.');
        $db = \Config\Database::connect();
        $version = $db->table('budget_versions')->where('id', $id)->get()->getRowArray();
        if (!$version) return $this->failNotFound('Budget version not found.');
        $items = $db->table('gpb_items')->where('plan_version_id', $id)->orderBy('sort_order')->get()->getResultArray();
        $organization = json_decode($version['organization_data'] ?? '{}', true) ?: ['year' => $version['fiscal_year']];
        $payload = $this->request->jwtPayload ?? [];
        $actorId = isset($payload['uid']) ? (int)$payload['uid'] : (isset($payload['id']) ? (int)$payload['id'] : null);
        try {
            $service = new \App\Libraries\BudgetVersionService($db);
            $newVersion = $service->createReplacement(
                (int)$version['fiscal_year'], $organization, $items, 'restore', $actorId
            );
            $service->restoreMonitoringSnapshot(
                (int)$version['id'],
                (int)$newVersion['id'],
                (int)$version['fiscal_year']
            );
            return $this->respond(['status' => 'success', 'version' => $newVersion]);
        } catch (\Throwable $e) {
            log_message('error', 'Budget version restore failed: {message}', ['message' => $e->getMessage()]);
            return $this->failServerError('Restore failed and was rolled back.');
        }
    }
}
