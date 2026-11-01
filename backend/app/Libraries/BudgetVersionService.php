<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

class BudgetVersionService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    public function active(int $fiscalYear): ?array
    {
        if (!$this->db->tableExists('budget_versions')) {
            return null;
        }
        return $this->db->table('budget_versions')
            ->where(['fiscal_year' => $fiscalYear, 'status' => 'active'])
            ->orderBy('id', 'DESC')->get()->getRowArray() ?: null;
    }

    public function ensureActive(int $fiscalYear, array $organization = [], ?int $actorId = null): array
    {
        $organization = $this->normalizeOrganization($organization, $fiscalYear);
        $active = $this->active($fiscalYear);
        if ($active) {
            return $active;
        }

        $latest = $this->db->table('budget_versions')
            ->where('fiscal_year', $fiscalYear)->orderBy('version_number', 'DESC')
            ->get()->getRowArray();
        $version = ((int)($latest['version_number'] ?? 0)) + 1;
        $this->db->table('budget_versions')->insert([
            'fiscal_year' => $fiscalYear,
            'version_number' => $version,
            'status' => 'active',
            'source' => 'initial',
            'organization_data' => json_encode($organization),
            'created_by' => $actorId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return $this->db->table('budget_versions')->where('id', $this->db->insertID())->get()->getRowArray();
    }

    public function saveInPlace(int $fiscalYear, array $organization, array $items, ?int $actorId = null): array
    {
        $organization = $this->normalizeOrganization($organization, $fiscalYear);
        $organization['otherSources'] = $this->calculateOtherSources($items);
        $this->db->transStart();
        $version = $this->ensureActive($fiscalYear, $organization, $actorId);
        $existing = $this->db->table('gpb_items')->where('plan_version_id', $version['id'])->get()->getResultArray();
        $existingById = [];
        $existingByKey = [];
        foreach ($existing as $row) {
            $existingById[(string)$row['id']] = $row;
            if (!empty($row['item_key'])) {
                $existingByKey[$row['item_key']] = $row;
            }
        }

        $seen = [];
        foreach ($items as $index => $item) {
            $row = $this->normalizeItem($item, $fiscalYear, $index + 1);
            $match = null;
            if (!empty($item['id']) && isset($existingById[(string)$item['id']])) {
                $match = $existingById[(string)$item['id']];
            } elseif (!empty($item['item_key']) && isset($existingByKey[$item['item_key']])) {
                $match = $existingByKey[$item['item_key']];
            }
            $row['item_key'] = $match['item_key'] ?? ($item['item_key'] ?? $this->newKey());
            $row['plan_version_id'] = $version['id'];
            if ($match) {
                $this->db->table('gpb_items')->where('id', $match['id'])->update($row);
                $seen[] = (int)$match['id'];
            } else {
                $this->db->table('gpb_items')->insert($row);
                $seen[] = (int)$this->db->insertID();
            }
        }

        $removed = array_diff(array_keys($existingById), array_map('strval', $seen));
        foreach ($removed as $id) {
            if ($this->hasDependents((int)$id)) {
                $this->db->transRollback();
                throw new \RuntimeException('Cannot delete GPB item ' . $id . ' because submitted documents or allocations depend on it.');
            }
            $this->db->table('gpb_items')->where('id', $id)->delete();
        }
        $this->db->table('budget_versions')->where('id', $version['id'])->update([
            'organization_data' => json_encode($organization),
        ]);
        $this->db->transComplete();
        if (!$this->db->transStatus()) {
            throw new \RuntimeException('Plan save failed and was rolled back.');
        }
        return $version;
    }

    public function createReplacement(int $fiscalYear, array $organization, array $items, string $source, ?int $actorId = null): array
    {
        $organization = $this->normalizeOrganization($organization, $fiscalYear);
        $organization['otherSources'] = $this->calculateOtherSources($items);
        $old = $this->active($fiscalYear);
        $oldRows = $old ? $this->db->table('gpb_items')->where('plan_version_id', $old['id'])->get()->getResultArray() : [];
        $oldById = [];
        foreach ($oldRows as $oldRow) {
            $oldById[(string)$oldRow['id']] = $oldRow;
        }
        $keys = [];
        foreach ($items as $item) {
            $key = trim((string)($item['item_key'] ?? ''));
            if ($key === '' && !empty($item['id']) && isset($oldById[(string)$item['id']])) {
                $key = (string)$oldById[(string)$item['id']]['item_key'];
            }
            if ($key !== '') {
                if (isset($keys[$key])) {
                    throw new \InvalidArgumentException('Duplicate item_key in replacement data: ' . $key);
                }
                $keys[$key] = true;
            }
        }
        $this->db->transStart();
        $latest = $this->db->table('budget_versions')->where('fiscal_year', $fiscalYear)
            ->orderBy('version_number', 'DESC')->get()->getRowArray();
        $versionNumber = ((int)($latest['version_number'] ?? 0)) + 1;
        if ($old) {
            $this->db->table('budget_versions')->where('id', $old['id'])->update([
                'status' => 'closed', 'closed_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->db->table('budget_versions')->insert([
            'fiscal_year' => $fiscalYear, 'version_number' => $versionNumber,
            'status' => 'active', 'source' => $source,
            'organization_data' => json_encode($organization),
            'created_by' => $actorId, 'created_at' => date('Y-m-d H:i:s'),
        ]);
        $versionId = (int)$this->db->insertID();
        foreach ($items as $index => $item) {
            $row = $this->normalizeItem($item, $fiscalYear, $index + 1);
            $key = trim((string)($item['item_key'] ?? ''));
            if ($key === '' && !empty($item['id']) && isset($oldById[(string)$item['id']])) {
                $key = (string)$oldById[(string)$item['id']]['item_key'];
            }
            if ($key === '') {
                $key = $this->newKey();
            }
            $keys[$key] = true;
            $row['plan_version_id'] = $versionId;
            $row['item_key'] = $key;
            $this->db->table('gpb_items')->insert($row);
        }
        $this->captureMonitoringSnapshot($versionId, $fiscalYear);
        $this->db->transComplete();
        if (!$this->db->transStatus()) {
            throw new \RuntimeException('Plan replacement failed and was rolled back.');
        }
        return $this->db->table('budget_versions')->where('id', $versionId)->get()->getRowArray();
    }

    public function hasDuplicateVersion(int $fiscalYear, array $organization, array $items): bool
    {
        $organization = $this->normalizeOrganization($organization, $fiscalYear);
        $organization['otherSources'] = $this->calculateOtherSources($items);
        $incoming = $this->canonicalVersionData($organization, $items, $fiscalYear);

        $versions = $this->db->table('budget_versions')
            ->where('fiscal_year', $fiscalYear)
            ->get()->getResultArray();
        foreach ($versions as $version) {
            $rows = $this->db->table('gpb_items')
                ->where('plan_version_id', $version['id'])
                ->get()->getResultArray();
            if ($incoming === $this->canonicalVersionData(
                json_decode($version['organization_data'] ?? '{}', true) ?: [],
                $rows,
                $fiscalYear
            )) {
                return true;
            }
        }

        return false;
    }

    public function captureMonitoringSnapshot(int $versionId, int $fiscalYear): void
    {
        if (!$this->db->tableExists('budget_version_monitoring_snapshots')) {
            return;
        }

        $items = $this->db->table('gpb_items')->where('plan_version_id', $versionId)->get()->getResultArray();
        $rows = [];
        $totalBudget = 0.0;
        $totalUtilized = 0.0;
        $totalPending = 0.0;
        $allocationRows = $this->db->table('budget_item_mandate_allocations bima')
            ->select('bima.mandate_id, SUM(bima.allocated_amount) as total_utilized')
            ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
            ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
            ->where('bima.item_type', 'AR')
            ->where('ar.status', 'Verified')
            ->where('ar.deleted_at', null)
            ->groupBy('bima.mandate_id')
            ->get()->getResultArray();
        $utilizedByGpbId = [];
        foreach ($allocationRows as $allocationRow) {
            $utilizedByGpbId[(int)$allocationRow['mandate_id']] = (float)$allocationRow['total_utilized'];
        }

        foreach ($items as $item) {
            $ids = [$item['id']];
            if (!empty($item['item_key'])) {
                $ids = array_column(
                    $this->db->table('gpb_items')->select('id')->where('item_key', $item['item_key'])->get()->getResultArray(),
                    'id'
                );
            }
            $ids = array_values(array_unique(array_map('intval', $ids)));
            $designs = $this->db->table('activity_design ad')
                ->select('ad.control_number, ad.proposed_budget')
                ->join('activity_design_mandates adm', 'adm.act_design_id = ad.act_design_id', 'left')
                ->groupStart()
                    ->whereIn('adm.mandate_id', $ids)
                    ->orWhereIn('ad.gpb_id', $ids)
                ->groupEnd()
                ->where('ad.status', 'Approved')
                ->where('ad.deleted_at', null)
                ->groupBy('ad.act_design_id')
                ->get()->getResultArray();

            $utilized = 0.0;
            $pending = 0.0;
            $actualMale = 0;
            $actualFemale = 0;
            foreach ($designs as $design) {
                $report = $this->db->table('accomplishment_report')
                    ->where('control_number', $design['control_number'])
                    ->whereIn('status', ['Completed', 'Verified', 'Approved'])
                    ->where('deleted_at', null)->get()->getRowArray();
                if ($report) {
                    $actualMale += (int)($report['male'] ?? 0);
                    $actualFemale += (int)($report['female'] ?? 0);
                } else {
                    $pending += (float)$design['proposed_budget'];
                }
            }
            $utilized = array_sum(array_map(
                static fn (int $lineageId): float => $utilizedByGpbId[$lineageId] ?? 0.0,
                $ids
            ));

            $allocated = (float)$item['budget'];
            $rows[] = [
                'gpb_id' => (int)$item['id'],
                'item_key' => $item['item_key'] ?? null,
                'mandate' => $item['mandate'],
                'activity' => $item['activity'],
                'allocated' => $allocated,
                'utilized' => $utilized,
                'actual_result_m' => $actualMale,
                'actual_result_f' => $actualFemale,
                'pending_approved' => $pending,
                'remaining' => $allocated - $utilized - $pending,
                'utilization_rate' => $allocated > 0 ? ($utilized / $allocated) * 100 : 0,
            ];
            $totalBudget += $allocated;
            $totalUtilized += $utilized;
            $totalPending += $pending;
        }

        $this->db->table('budget_version_monitoring_snapshots')->insert([
            'plan_version_id' => $versionId,
            'fiscal_year' => $fiscalYear,
            'total_budget' => $totalBudget,
            'total_utilized' => $totalUtilized,
            'total_pending_approved' => $totalPending,
            'remaining_balance' => $totalBudget - $totalUtilized - $totalPending,
            'utilization_rate' => $totalBudget > 0 ? ($totalUtilized / $totalBudget) * 100 : 0,
            'rows_data' => json_encode($rows),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function restoreMonitoringSnapshot(int $sourceVersionId, int $targetVersionId, int $fiscalYear): void
    {
        if (!$this->db->tableExists('budget_version_monitoring_snapshots')) {
            return;
        }

        $source = $this->db->table('budget_version_monitoring_snapshots')
            ->where('plan_version_id', $sourceVersionId)->get()->getRowArray();
        if (!$source) {
            return;
        }

        $targetItems = $this->db->table('gpb_items')
            ->select('id, item_key')
            ->where('plan_version_id', $targetVersionId)->get()->getResultArray();
        $targetIdsByKey = [];
        foreach ($targetItems as $item) {
            if (!empty($item['item_key'])) {
                $targetIdsByKey[$item['item_key']] = (int)$item['id'];
            }
        }

        $rows = json_decode($source['rows_data'] ?? '[]', true) ?: [];
        $totalUtilized = 0.0;
        foreach ($rows as &$row) {
            $key = (string)($row['item_key'] ?? '');
            if ($key !== '' && isset($targetIdsByKey[$key])) {
                $row['gpb_id'] = $targetIdsByKey[$key];
            }
            $lineageIds = $key !== ''
                ? array_column($this->db->table('gpb_items')->select('id')->where('item_key', $key)->get()->getResultArray(), 'id')
                : [(int)($row['gpb_id'] ?? 0)];
            $row['utilized'] = $this->calculateVerifiedAllocatedAmount($lineageIds);
            $totalUtilized += (float)$row['utilized'];
        }
        unset($row);

        $this->db->table('budget_version_monitoring_snapshots')
            ->where('plan_version_id', $targetVersionId)
            ->update([
                'fiscal_year' => $fiscalYear,
                'total_budget' => (float)$source['total_budget'],
                'total_utilized' => $totalUtilized,
                'total_pending_approved' => (float)$source['total_pending_approved'],
                'remaining_balance' => (float)$source['remaining_balance'],
                'utilization_rate' => (float)$source['utilization_rate'],
                'rows_data' => json_encode($rows),
            ]);
    }

    private function calculateVerifiedAllocatedAmount(array $gpbIds): float
    {
        $gpbIds = array_values(array_unique(array_filter(array_map('intval', $gpbIds))));
        if (!$gpbIds) {
            return 0.0;
        }

        $row = $this->db->table('budget_item_mandate_allocations bima')
            ->selectSum('bima.allocated_amount', 'total_utilized')
            ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
            ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
            ->whereIn('bima.mandate_id', $gpbIds)
            ->where('bima.item_type', 'AR')
            ->where('ar.status', 'Verified')
            ->where('ar.deleted_at', null)
            ->get()->getRowArray();

        return (float)($row['total_utilized'] ?? 0);
    }

    private function calculateOtherSources(array $items): float
    {
        $total = 0.0;
        foreach ($items as $item) {
            $lines = $item['budgetLines'] ?? $item['budget_lines'] ?? [];
            if (is_string($lines)) {
                $lines = json_decode($lines, true) ?: [];
            }
            foreach ($lines as $line) {
                if (strtolower(trim((string)($line['source'] ?? ''))) === 'other') {
                    $total += (float)($line['amount'] ?? 0);
                }
            }
        }
        return $total;
    }

    private function normalizeItem(array $item, int $year, int $sort): array
    {
        $lines = $item['budgetLines'] ?? $item['budget_lines'] ?? [];
        if (is_string($lines)) {
            $decodedLines = json_decode($lines, true);
            $lines = is_array($decodedLines) ? $decodedLines : [];
        }

        if (!is_array($lines)) {
            $lines = [];
        }
        $budget = 0.0;
        foreach ($lines as $line) {
            $budget += (float)($line['amount'] ?? 0);
        }
        return [
            'fiscal_year' => $year, 'section' => $item['section'] ?? 'client_focused',
            'sort_order' => $sort, 'mandate' => $item['mandate'] ?? null,
            'cause' => $item['cause'] ?? null, 'objective' => $item['result'] ?? ($item['objective'] ?? null),
            'result' => $item['result'] ?? ($item['objective'] ?? null), 'ppa' => $item['mfo'] ?? ($item['ppa'] ?? null),
            'mfo' => $item['mfo'] ?? ($item['ppa'] ?? null), 'activity' => $item['activity'] ?? null,
            'targets' => $item['indicators'] ?? ($item['targets'] ?? null),
            'indicators' => $item['indicators'] ?? ($item['targets'] ?? null),
            'budget' => $budget, 'source' => $item['source'] ?? 'GAA',
            'office' => $item['responsible'] ?? ($item['office'] ?? null),
            'responsible' => $item['responsible'] ?? ($item['office'] ?? null),
            'budget_lines' => json_encode($lines), 'updated_at' => date('Y-m-d H:i:s'),
        ];
    }

    private function normalizeOrganization(array $organization, int $fiscalYear): array
    {
        $organization['year'] = $fiscalYear;
        $organization['totalOrgBudget'] = (float)($organization['totalOrgBudget'] ?? 0);
        $organization['otherSources'] = (float)($organization['otherSources'] ?? 0);
        return $organization;
    }

    private function canonicalVersionData(array $organization, array $items, int $fiscalYear): string
    {
        $canonicalItems = [];
        foreach ($items as $index => $item) {
            $row = $this->normalizeItem($item, $fiscalYear, $index + 1);
            $lines = json_decode($row['budget_lines'], true) ?: [];
            $canonicalLines = array_map(static function (array $line): array {
                return [
                    'label' => trim((string)($line['label'] ?? '')),
                    'amount' => (float)($line['amount'] ?? 0),
                    'source' => trim((string)($line['source'] ?? '')),
                ];
            }, $lines);
            $row['budget_lines'] = $canonicalLines;
            unset($row['sort_order'], $row['updated_at']);
            $canonicalItems[] = $row;
        }

        usort($canonicalItems, static function (array $left, array $right): int {
            return strcmp(json_encode($left), json_encode($right));
        });

        return json_encode([
            'year' => $fiscalYear,
            'totalOrgBudget' => (float)($organization['totalOrgBudget'] ?? 0),
            'otherSources' => (float)($organization['otherSources'] ?? 0),
            'items' => $canonicalItems,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function newKey(): string
    {
        return bin2hex(random_bytes(16));
    }

    private function hasDependents(int $id): bool
    {
        foreach ([
            ['activity_design_mandates', 'mandate_id'],
            ['activity_design_issues', 'issue_id'],
            ['budget_item_mandate_allocations', 'mandate_id'],
        ] as [$table, $column]) {
            if ($this->db->tableExists($table) && $this->db->table($table)->where($column, $id)->countAllResults() > 0) {
                return true;
            }
        }
        return false;
    }
}
