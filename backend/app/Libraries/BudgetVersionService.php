<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

class BudgetVersionService
{
    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?: \Config\Database::connect();
    }

    /**
     * Compute remaining and utilization rate using the shared institutional formula
     */
    public static function calculateMetrics(float $allocated, float $utilized, float $pending): array
    {
        $remaining = max(0.0, $allocated - ($utilized + $pending));
        $utilizationRate = $allocated > 0 ? round(($utilized / $allocated) * 100, 1) : 0.0;
        return [
            'remaining' => $remaining,
            'utilization_rate' => $utilizationRate,
        ];
    }

    /**
     * Guard: asserts that the given GPB item belongs to an active (open) version.
     * Rejects writes to rows belonging to a closed version.
     *
     * @throws \InvalidArgumentException if the item is part of a closed budget version
     */
    public function assertItemModifiable(int $gpbItemId): void
    {
        $item = $this->db->table('gpb_items')
            ->select('gpb_items.id, gpb_items.plan_version_id, bv.status')
            ->join('budget_versions bv', 'bv.id = gpb_items.plan_version_id', 'left')
            ->where('gpb_items.id', $gpbItemId)
            ->get()
            ->getRowArray();

        if ($item && !empty($item['plan_version_id']) && ($item['status'] ?? '') === 'closed') {
            throw new \InvalidArgumentException('Cannot modify or delete an item from a closed budget version. Historical records are read-only retained archives.');
        }
    }

    public function active(int $fiscalYear): ?array
    {
        if (!$this->db->tableExists('budget_versions')) {
            return null;
        }

        $row = $this->db->table('budget_versions')
            ->where('fiscal_year', $fiscalYear)
            ->where('status', 'active')
            ->orderBy('version_number', 'DESC')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    public function nextVersionNumber(int $fiscalYear): int
    {
        $max = $this->db->table('budget_versions')
            ->where('fiscal_year', $fiscalYear)
            ->selectMax('version_number', 'max_version')
            ->get()
            ->getRowArray();

        return (int)($max['max_version'] ?? 0) + 1;
    }

    public function hasDuplicateVersion(int $fiscalYear, array $organization, array $items): bool
    {
        $fields = $this->db->getFieldNames('budget_versions');
        if (!in_array('content_hash', $fields, true)) {
            return false;
        }

        $hash = $this->computeContentHash($organization, $items);
        $found = $this->db->table('budget_versions')
            ->where('fiscal_year', $fiscalYear)
            ->where('content_hash', $hash)
            ->countAllResults();

        return $found > 0;
    }

    public function computeContentHash(array $organization, array $items): string
    {
        $normalizedItems = array_map(function ($item) {
            return [
                'item_key'     => $item['item_key'] ?? null,
                'section'      => $item['section'] ?? null,
                'mandate'      => $item['mandate'] ?? null,
                'cause'        => $item['cause'] ?? null,
                'gad_objective'=> $item['gad_objective'] ?? null,
                'relevant_org' => $item['relevant_org'] ?? null,
                'gad_activity' => $item['gad_activity'] ?? null,
                'target'       => $item['target'] ?? null,
                'budget_lines' => $item['budget_lines'] ?? null,
                'total_budget' => isset($item['total_budget']) ? (float)$item['total_budget'] : null,
                'raw_budget'   => isset($item['raw_budget']) ? (float)$item['raw_budget'] : null,
                'source'       => $item['source'] ?? null,
                'responsible'  => $item['responsible'] ?? null,
            ];
        }, $items);

        return hash('sha256', json_encode([
            'org'   => $organization,
            'items' => $normalizedItems,
        ]));
    }

    /**
     * Pre-close outgoing active version: snapshot captured BEFORE marking closed
     */
    public function closeActiveVersion(array $oldVersion, int $fiscalYear): void
    {
        if ($this->db->tableExists('budget_version_monitoring_snapshots')) {
            $this->captureMonitoringSnapshot((int)$oldVersion['id'], $fiscalYear);
        }

        $this->db->table('budget_versions')
            ->where('id', $oldVersion['id'])
            ->update([
                'status'    => 'closed',
                'closed_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function createReplacement(
        int $fiscalYear,
        array $organization,
        array $items,
        string $source = 'reset',
        ?int $actorId = null
    ): array {
        $this->db->transStart();

        $oldActive = $this->active($fiscalYear);
        if ($oldActive) {
            $this->closeActiveVersion($oldActive, $fiscalYear);
        }

        $nextVersion = $this->nextVersionNumber($fiscalYear);
        $contentHash = $this->computeContentHash($organization, $items);

        $versionFields = $this->db->getFieldNames('budget_versions');
        $insertVersionData = [
            'fiscal_year'       => $fiscalYear,
            'version_number'    => $nextVersion,
            'status'            => 'active',
            'source'            => $source,
            'organization_data' => json_encode($organization),
            'created_by'        => $actorId,
            'created_at'        => date('Y-m-d H:i:s'),
        ];
        if (in_array('content_hash', $versionFields, true)) {
            $insertVersionData['content_hash'] = $contentHash;
        }

        $this->db->table('budget_versions')->insert($insertVersionData);
        $versionId = (int)$this->db->insertID();

        if (!empty($items)) {
            $gpbFields = $this->db->getFieldNames('gpb_items');
            $itemRows = [];
            foreach ($items as $idx => $item) {
                $itemKey = $item['item_key'] ?? null;
                if (empty($itemKey)) {
                    $itemKey = $this->generateItemKey($item);
                }
                $candidateRow = [
                    'plan_version_id' => $versionId,
                    'fiscal_year'     => $fiscalYear,
                    'sort_order'      => $item['sort_order'] ?? $idx,
                    'item_key'        => $itemKey,
                    'section'         => $item['section'] ?? null,
                    'mandate'         => $item['mandate'] ?? null,
                    'cause'           => $item['cause'] ?? null,
                    'objective'       => $item['objective'] ?? $item['result'] ?? $item['gad_objective'] ?? null,
                    'result'          => $item['result'] ?? $item['objective'] ?? null,
                    'ppa'             => $item['ppa'] ?? $item['mfo'] ?? $item['relevant_org'] ?? null,
                    'mfo'             => $item['mfo'] ?? $item['ppa'] ?? null,
                    'activity'        => $item['activity'] ?? $item['gad_activity'] ?? null,
                    'targets'         => $item['targets'] ?? $item['indicators'] ?? $item['target'] ?? null,
                    'indicators'      => $item['indicators'] ?? $item['targets'] ?? null,
                    'budget'          => (float)($item['budget'] ?? $item['total_budget'] ?? $item['raw_budget'] ?? 0),
                    'budget_lines'    => is_array($item['budget_lines'] ?? null)
                        ? json_encode($item['budget_lines'])
                        : ($item['budget_lines'] ?? null),
                    'source'          => $item['source'] ?? null,
                    'office'          => $item['office'] ?? $item['responsible'] ?? null,
                    'responsible'     => $item['responsible'] ?? $item['office'] ?? null,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];

                $cleanRow = [];
                foreach ($candidateRow as $col => $val) {
                    if (in_array($col, $gpbFields, true)) {
                        $cleanRow[$col] = $val;
                    }
                }
                $itemRows[] = $cleanRow;
            }
            $this->db->table('gpb_items')->insertBatch($itemRows);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            throw new \RuntimeException('Failed to create new budget version.');
        }

        return $this->active($fiscalYear) ?: ['id' => $versionId, 'version_number' => $nextVersion];
    }

    /**
     * Save edits to the active plan version (or legacy fiscal-year items) in place,
     * preserving existing gpb_items primary keys so linked Activity Designs and
     * mandate budget allocations remain intact.
     */
    public function saveInPlace(
        int $fiscalYear,
        array $organization,
        array $items,
        ?int $actorId = null
    ): ?array {
        $this->db->transStart();

        $hasVersionsTable = $this->db->tableExists('budget_versions');
        $activeVersion = $hasVersionsTable ? $this->active($fiscalYear) : null;
        $versionId = $activeVersion ? (int)$activeVersion['id'] : null;

        if ($hasVersionsTable) {
            $contentHash = $this->computeContentHash($organization, $items);
            $versionFields = $this->db->getFieldNames('budget_versions');

            if ($activeVersion) {
                $updateVersion = [
                    'organization_data' => json_encode($organization),
                ];
                if (in_array('content_hash', $versionFields, true)) {
                    $updateVersion['content_hash'] = $contentHash;
                }
                if (in_array('updated_at', $versionFields, true)) {
                    $updateVersion['updated_at'] = date('Y-m-d H:i:s');
                }
                $this->db->table('budget_versions')
                    ->where('id', $versionId)
                    ->update($updateVersion);
            } else {
                $nextVersion = $this->nextVersionNumber($fiscalYear);
                $insertVersionData = [
                    'fiscal_year'       => $fiscalYear,
                    'version_number'    => $nextVersion,
                    'status'            => 'active',
                    'source'            => 'manual',
                    'organization_data' => json_encode($organization),
                    'created_by'        => $actorId,
                    'created_at'        => date('Y-m-d H:i:s'),
                ];
                if (in_array('content_hash', $versionFields, true)) {
                    $insertVersionData['content_hash'] = $contentHash;
                }
                $this->db->table('budget_versions')->insert($insertVersionData);
                $versionId = (int)$this->db->insertID();

                // Attach any existing unversioned items for this fiscal year to this initial version
                $gpbFieldsInit = $this->db->getFieldNames('gpb_items');
                if (in_array('plan_version_id', $gpbFieldsInit, true)) {
                    $this->db->table('gpb_items')
                        ->where('fiscal_year', $fiscalYear)
                        ->where('plan_version_id', null)
                        ->update(['plan_version_id' => $versionId]);
                }
            }
        }

        if ($this->db->tableExists('settings')) {
            $settingModel = new \App\Models\SettingModel();
            foreach ($organization as $key => $val) {
                if ($key !== 'otherSources') {
                    $settingModel->saveSetting((string)$key, $val, $fiscalYear);
                }
            }
        }

        $gpbFields = $this->db->getFieldNames('gpb_items');
        $hasPlanVersionCol = in_array('plan_version_id', $gpbFields, true);

        $existingQuery = $this->db->table('gpb_items')->select('id');
        if ($hasPlanVersionCol && $versionId !== null) {
            $existingQuery->where('plan_version_id', $versionId);
        } else {
            $existingQuery->where('fiscal_year', $fiscalYear);
        }
        $existingIds = array_map('intval', array_column($existingQuery->get()->getResultArray(), 'id'));
        $existingIdSet = array_flip($existingIds);
        $keptIds = [];
        $now = date('Y-m-d H:i:s');

        foreach ($items as $idx => $item) {
            $rawLines = $item['budget_lines'] ?? $item['budgetLines'] ?? null;
            $linesArray = is_array($rawLines)
                ? $rawLines
                : (is_string($rawLines) ? (json_decode($rawLines, true) ?: []) : []);

            $linesSum = 0.0;
            $sources = [];
            foreach ($linesArray as $line) {
                $linesSum += (float)($line['amount'] ?? 0);
                if (!empty($line['source'])) {
                    $sources[] = (string)$line['source'];
                }
            }
            $resolvedBudget = !empty($linesArray)
                ? $linesSum
                : (float)($item['budget'] ?? $item['total_budget'] ?? $item['raw_budget'] ?? 0);
            $resolvedSource = !empty($sources)
                ? implode(', ', array_unique($sources))
                : ($item['source'] ?? 'GAA');

            $itemKey = $item['item_key'] ?? null;
            if (empty($itemKey)) {
                $itemKey = $this->generateItemKey([
                    'section'      => $item['section'] ?? '',
                    'mandate'      => $item['mandate'] ?? '',
                    'gad_activity' => $item['activity'] ?? $item['gad_activity'] ?? '',
                ]);
            }

            $candidateRow = [
                'plan_version_id' => $versionId,
                'fiscal_year'     => $fiscalYear,
                'sort_order'      => $item['sort_order'] ?? ($idx + 1),
                'item_key'        => $itemKey,
                'section'         => $item['section'] ?? 'client',
                'mandate'         => $item['mandate'] ?? null,
                'cause'           => $item['cause'] ?? null,
                'objective'       => $item['objective'] ?? $item['result'] ?? $item['gad_objective'] ?? null,
                'result'          => $item['result'] ?? $item['objective'] ?? null,
                'ppa'             => $item['ppa'] ?? $item['mfo'] ?? $item['relevant_org'] ?? null,
                'mfo'             => $item['mfo'] ?? $item['ppa'] ?? null,
                'activity'        => $item['activity'] ?? $item['gad_activity'] ?? null,
                'targets'         => $item['targets'] ?? $item['indicators'] ?? $item['target'] ?? null,
                'indicators'      => $item['indicators'] ?? $item['targets'] ?? null,
                'budget'          => $resolvedBudget,
                'budget_lines'    => is_array($rawLines) ? json_encode($rawLines) : $rawLines,
                'source'          => $resolvedSource,
                'office'          => $item['office'] ?? $item['responsible'] ?? null,
                'responsible'     => $item['responsible'] ?? $item['office'] ?? null,
                'updated_at'      => $now,
            ];

            $cleanRow = [];
            foreach ($candidateRow as $col => $val) {
                if (in_array($col, $gpbFields, true)) {
                    $cleanRow[$col] = $val;
                }
            }

            $rawId = $item['id'] ?? null;
            $numericId = (is_numeric($rawId) && (int)$rawId > 0) ? (int)$rawId : null;

            if ($numericId !== null && isset($existingIdSet[$numericId])) {
                $this->db->table('gpb_items')->where('id', $numericId)->update($cleanRow);
                $keptIds[] = $numericId;
            } else {
                if (in_array('created_at', $gpbFields, true)) {
                    $cleanRow['created_at'] = $now;
                }
                $this->db->table('gpb_items')->insert($cleanRow);
                $keptIds[] = (int)$this->db->insertID();
            }
        }

        $idsToDelete = array_values(array_diff($existingIds, $keptIds));
        if (!empty($idsToDelete)) {
            $deleteQuery = $this->db->table('gpb_items')->whereIn('id', $idsToDelete);
            if ($hasPlanVersionCol && $versionId !== null) {
                $deleteQuery->where('plan_version_id', $versionId);
            } else {
                $deleteQuery->where('fiscal_year', $fiscalYear);
            }
            $deleteQuery->delete();
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            throw new \RuntimeException('Failed to save GPB plan changes.');
        }

        return $hasVersionsTable ? $this->active($fiscalYear) : null;
    }

    public function generateItemKey(array $item): string
    {
        $fingerprint = implode('|', [
            trim((string)($item['section'] ?? '')),
            trim((string)($item['mandate'] ?? '')),
            trim((string)($item['gad_activity'] ?? '')),
        ]);
        return substr(hash('sha256', $fingerprint), 0, 16);
    }

    /**
     * Batch-optimized snapshot capture: captures monitoring state as of version close.
     * Reduced from 150+ queries to 4 batched queries with upsert.
     */
    public function captureMonitoringSnapshot(int $planVersionId, int $fiscalYear): void
    {
        $items = $this->db->table('gpb_items')
            ->where('plan_version_id', $planVersionId)
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();

        $rows = [];
        $totalAllocated = 0.0;
        $totalUtilized  = 0.0;
        $totalPending   = 0.0;

        if (!empty($items)) {
            $itemIds = array_values(array_unique(array_map('intval', array_column($items, 'id'))));

            // Fetch all approved activity designs linked to this version's GPB items
            $approvedDesigns = $this->db->table('activity_design ad')
                ->select('ad.act_design_id, ad.control_number, ad.proposed_budget, adm.mandate_id as adm_mandate_id, ad.gpb_id')
                ->join('activity_design_mandates adm', 'adm.act_design_id = ad.act_design_id', 'left')
                ->groupStart()
                    ->whereIn('adm.mandate_id', $itemIds)
                    ->orWhereIn('ad.gpb_id', $itemIds)
                ->groupEnd()
                ->where('ad.status', 'Approved')
                ->where('ad.deleted_at', null)
                ->get()
                ->getResultArray();

            // Group by distinct activity design to prevent duplicates from multiple mandate joins
            $distinctDesigns = [];
            foreach ($approvedDesigns as $ad) {
                $adId = (int)$ad['act_design_id'];
                if (!isset($distinctDesigns[$adId])) {
                    $distinctDesigns[$adId] = [
                        'act_design_id'   => $adId,
                        'control_number'  => $ad['control_number'],
                        'proposed_budget' => (float)$ad['proposed_budget'],
                        'mandate_ids'     => [],
                    ];
                }
                if (!empty($ad['adm_mandate_id']) && in_array((int)$ad['adm_mandate_id'], $itemIds, true)) {
                    $distinctDesigns[$adId]['mandate_ids'][] = (int)$ad['adm_mandate_id'];
                }
                if (!empty($ad['gpb_id']) && in_array((int)$ad['gpb_id'], $itemIds, true)) {
                    $distinctDesigns[$adId]['mandate_ids'][] = (int)$ad['gpb_id'];
                }
            }

            $utilizedMap = [];
            $pendingMap  = [];

            foreach ($distinctDesigns as $design) {
                $ctrl = $design['control_number'];
                $report = null;
                if (!empty($ctrl)) {
                    $report = $this->db->table('accomplishment_report')
                        ->where('control_number', $ctrl)
                        ->whereIn('status', ['Completed', 'Verified', 'Approved'])
                        ->where('deleted_at', null)
                        ->get()
                        ->getRowArray();
                }

                $linkedMIds = array_values(array_unique($design['mandate_ids']));
                if (empty($linkedMIds)) {
                    // Fallback to all items if mandate was unspecified
                    $linkedMIds = $itemIds;
                }
                $mandateCount = count($linkedMIds);

                if ($report) {
                    $reportSum = $this->db->table('accomplishment_budget_items')
                        ->selectSum('amount')
                        ->where('accomplishment_report_id', $report['id'])
                        ->get()
                        ->getRow()->amount ?? 0.0;
                    $arAmount = (float)$reportSum;
                    $totalUtilized += $arAmount;

                    $split = $arAmount / $mandateCount;
                    foreach ($linkedMIds as $mId) {
                        $utilizedMap[$mId] = ($utilizedMap[$mId] ?? 0.0) + $split;
                    }
                } else {
                    $adAmount = (float)$design['proposed_budget'];
                    $totalPending += $adAmount;

                    $split = $adAmount / $mandateCount;
                    foreach ($linkedMIds as $mId) {
                        $pendingMap[$mId] = ($pendingMap[$mId] ?? 0.0) + $split;
                    }
                }
            }

            // 3. Compile rows
            foreach ($items as $item) {
                $mId = (int)$item['id'];
                $allocated = (float)($item['budget'] ?? $item['total_budget'] ?? $item['raw_budget'] ?? 0);
                $utilized  = $utilizedMap[$mId] ?? 0.0;
                $pending   = $pendingMap[$mId] ?? 0.0;

                $metrics = self::calculateMetrics($allocated, $utilized, $pending);
                $totalAllocated += $allocated;

                $rows[] = [
                    'gpb_item_id'      => $item['id'],
                    'item_key'         => $item['item_key'] ?? null,
                    'section'          => $item['section'] ?? '',
                    'activity'         => $item['activity'] ?? $item['gad_activity'] ?? $item['mandate'] ?? '',
                    'allocated'        => $allocated,
                    'utilized'         => $utilized,
                    'pending'          => $pending,
                    'remaining'        => $metrics['remaining'],
                    'utilization_rate' => $metrics['utilization_rate'],
                ];
            }
        }

        $overallMetrics = self::calculateMetrics($totalAllocated, $totalUtilized, $totalPending);

        $fields = $this->db->getFieldNames('budget_version_monitoring_snapshots');
        $hasCapturedAt = in_array('captured_at', $fields, true);

        $snapshotData = [
            'plan_version_id' => $planVersionId,
            'rows_data'       => json_encode($rows),
        ];

        if (in_array('fiscal_year', $fields, true)) {
            $snapshotData['fiscal_year'] = $fiscalYear;
        }

        if ($hasCapturedAt) {
            $snapshotData['captured_at'] = date('Y-m-d H:i:s');
            $snapshotData['total_allocated'] = $totalAllocated;
            $snapshotData['total_utilized'] = $totalUtilized;
            $snapshotData['total_pending'] = $totalPending;
            $snapshotData['total_remaining'] = $overallMetrics['remaining'];
            $snapshotData['total_utilization_rate'] = $overallMetrics['utilization_rate'];
        } else {
            // Backward-compatibility with original migration column names
            $snapshotData['created_at'] = date('Y-m-d H:i:s');
            $snapshotData['total_budget'] = $totalAllocated;
            $snapshotData['total_utilized'] = $totalUtilized;
            $snapshotData['total_pending_approved'] = $totalPending;
            $snapshotData['remaining_balance'] = $overallMetrics['remaining'];
            $snapshotData['utilization_rate'] = $overallMetrics['utilization_rate'];
        }

        // Upsert snapshot
        $existing = $this->db->table('budget_version_monitoring_snapshots')
            ->where('plan_version_id', $planVersionId)
            ->get()
            ->getRowArray();

        if ($existing) {
            $this->db->table('budget_version_monitoring_snapshots')
                ->where('plan_version_id', $planVersionId)
                ->update($snapshotData);
        } else {
            $this->db->table('budget_version_monitoring_snapshots')->insert($snapshotData);
        }
    }
}
