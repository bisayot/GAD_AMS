<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

/**
 * GadUtilizationService
 *
 * Builds the AR-based rows for the Annual GAD Budget Utilization Excel export
 * ("GAD AR" sheet), matching the GAD Budget Distribution by Mandate allocation
 * feature and the active Plan & Budget (GPB) version for a given fiscal year.
 */
class GadUtilizationService
{
    public const SECTION_ORDER = [
        'client_focused',
        'organization_focused',
        'attributed_program',
    ];

    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?: \Config\Database::connect();
    }

    /**
     * Normalize any stored section key ('client' / 'client_focused', etc.)
     * into the canonical template section key.
     */
    public static function normalizeSection(?string $raw): ?string
    {
        $key = strtolower(trim((string) $raw));
        return match ($key) {
            'client', 'client_focused'                         => 'client_focused',
            'org', 'organization', 'organization_focused'      => 'organization_focused',
            'attributed', 'attributed_program'                 => 'attributed_program',
            default                                            => null,
        };
    }

    /**
     * Return distinct available fiscal years (newest first), always including
     * the current calendar year.
     *
     * @return array<int, int>
     */
    public function getAvailableFiscalYears(): array
    {
        $years = [(int) date('Y')];

        if ($this->db->tableExists('budget_versions')) {
            $vRows = $this->db->table('budget_versions')
                ->distinct()
                ->select('fiscal_year')
                ->get()
                ->getResultArray();
            foreach ($vRows as $row) {
                $y = (int) ($row['fiscal_year'] ?? 0);
                if ($y >= 2000 && $y <= 2100) {
                    $years[] = $y;
                }
            }
        }

        if ($this->db->tableExists('gpb_items')) {
            $gRows = $this->db->table('gpb_items')
                ->distinct()
                ->select('fiscal_year')
                ->get()
                ->getResultArray();
            foreach ($gRows as $row) {
                $y = (int) ($row['fiscal_year'] ?? 0);
                if ($y >= 2000 && $y <= 2100) {
                    $years[] = $y;
                }
            }
        }

        $years = array_values(array_unique($years));
        rsort($years, SORT_NUMERIC);

        return $years;
    }

    /**
     * Build row specifications grouped by section for the requested fiscal year.
     *
     * @return array<string, array<int, array{
     *   mandate: ?string,
     *   activity: ?string,
     *   indicators: ?string,
     *   male: ?int,
     *   female: ?int,
     *   budget_line: ?string,
     *   month_date: ?string,
     *   ar_status: ?string,
     *   allocation: ?string,
     *   allocated: ?float,
     *   actual_exp: ?float,
     *   attrib_gaa: ?float
     * }>>
     */
    public function buildGroupedRows(int $fiscalYear): array
    {
        $grouped = array_fill_keys(self::SECTION_ORDER, []);

        $version = $this->db->tableExists('budget_versions')
            ? (new BudgetVersionService($this->db))->active($fiscalYear)
            : null;

        // 1. Fetch active GPB items for this fiscal year in Plan & Budget order.
        $gpbQuery = $this->db->table('gpb_items');
        if ($version) {
            $gpbQuery->where('plan_version_id', $version['id']);
        } else {
            $gpbQuery->where('fiscal_year', $fiscalYear);
        }
        $rawGpbItems = $gpbQuery
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($rawGpbItems)) {
            return $grouped;
        }

        // Merge identical mandate+cause+activity rows if any exist in the same version
        // (mirrors PlanController::getMandateStatistics so numbers match 1-to-1).
        $planEntries = [];
        $itemKeys = [];

        foreach ($rawGpbItems as $item) {
            $section = self::normalizeSection($item['section'] ?? '');
            if ($section === null) {
                continue;
            }

            $mandateRaw = trim((string) ($item['mandate'] ?? ''));
            $causeRaw   = trim((string) ($item['cause'] ?? ''));
            $actRaw     = trim((string) ($item['activity'] ?? ''));
            $indRaw     = trim((string) ($item['indicators'] ?? $item['targets'] ?? ''));

            $groupKey = $section . '|' . md5($mandateRaw . '|' . $causeRaw . '|' . $actRaw);

            if (!isset($planEntries[$groupKey])) {
                $planEntries[$groupKey] = [
                    'section'      => $section,
                    'mandate'      => $mandateRaw,
                    'activity'     => $actRaw,
                    'indicators'   => $indRaw,
                    'source'       => (string) ($item['source'] ?? 'GAA'),
                    'gpb_ids'      => [],
                    'item_keys'    => [],
                    'lineage_ids'  => [],
                    'budget_lines' => [],
                ];
            }

            $id = (int) $item['id'];
            $planEntries[$groupKey]['gpb_ids'][] = $id;
            $planEntries[$groupKey]['lineage_ids'][] = $id;

            $ik = trim((string) ($item['item_key'] ?? ''));
            if ($ik !== '') {
                $planEntries[$groupKey]['item_keys'][] = $ik;
                $itemKeys[] = $ik;
            }

            $decodedLines = isset($item['budget_lines']) ? json_decode((string) $item['budget_lines'], true) : [];
            if (is_array($decodedLines) && !empty($decodedLines)) {
                foreach ($decodedLines as $idx => $bl) {
                    $lineId = trim((string) ($bl['id'] ?? ''));
                    if ($lineId === '') {
                        $lineId = '__idx_' . $id . '_' . $idx;
                    }
                    $planEntries[$groupKey]['budget_lines'][$lineId] = [
                        'id'     => $lineId,
                        'label'  => trim((string) ($bl['label'] ?? '')),
                        'amount' => (float) ($bl['amount'] ?? 0),
                        'source' => trim((string) ($bl['source'] ?? ($item['source'] ?? 'GAA'))),
                    ];
                }
            } elseif ((float) ($item['budget'] ?? 0) > 0) {
                // Legacy fallback when budget_lines JSON was not populated
                $fallbackId = '__legacy_' . $id;
                $planEntries[$groupKey]['budget_lines'][$fallbackId] = [
                    'id'     => $fallbackId,
                    'label'  => $actRaw,
                    'amount' => (float) $item['budget'],
                    'source' => trim((string) ($item['source'] ?? 'GAA')),
                ];
            }
        }

        if (empty($planEntries)) {
            return $grouped;
        }

        // 2. Resolve fiscal-year-scoped lineage IDs in a single query, plus historical
        //    budget_lines definitions so renamed/replaced line IDs can still resolve.
        $historicalLinesById = [];
        if (!empty($itemKeys)) {
            $lineageRows = $this->db->table('gpb_items')
                ->select('id, item_key, budget_lines, source')
                ->where('fiscal_year', $fiscalYear)
                ->whereIn('item_key', array_values(array_unique($itemKeys)))
                ->get()
                ->getResultArray();

            $idsByKey = [];
            foreach ($lineageRows as $lr) {
                $lid = (int) $lr['id'];
                $lik = (string) $lr['item_key'];
                $idsByKey[$lik][] = $lid;

                $hLines = isset($lr['budget_lines']) ? json_decode((string) $lr['budget_lines'], true) : [];
                if (is_array($hLines)) {
                    foreach ($hLines as $hl) {
                        $hlId = trim((string) ($hl['id'] ?? ''));
                        if ($hlId !== '' && !isset($historicalLinesById[$hlId])) {
                            $historicalLinesById[$hlId] = [
                                'label'  => trim((string) ($hl['label'] ?? '')),
                                'amount' => (float) ($hl['amount'] ?? 0),
                                'source' => trim((string) ($hl['source'] ?? ($lr['source'] ?? 'GAA'))),
                            ];
                        }
                    }
                }
            }

            foreach ($planEntries as &$entry) {
                foreach ($entry['item_keys'] as $ik) {
                    if (!empty($idsByKey[$ik])) {
                        $entry['lineage_ids'] = array_merge($entry['lineage_ids'], $idsByKey[$ik]);
                    }
                }
                $entry['lineage_ids'] = array_values(array_unique(array_map('intval', $entry['lineage_ids'])));
            }
            unset($entry);
        }

        // Map every lineage mandate_id -> planEntry key(s)
        $mandateIdToEntryKeys = [];
        $allLineageIds = [];
        foreach ($planEntries as $eKey => $entry) {
            foreach ($entry['lineage_ids'] as $lid) {
                $mandateIdToEntryKeys[$lid][] = $eKey;
                $allLineageIds[$lid] = $lid;
            }
        }
        $allLineageIds = array_values($allLineageIds);

        if (empty($allLineageIds)) {
            return $grouped;
        }

        // 3. Batch-query AR mandate allocations (item_type = 'AR').
        $allocRows = $this->db->table('budget_item_mandate_allocations bima')
            ->select(
                'bima.mandate_id, bima.gpb_budget_line_id, SUM(bima.allocated_amount) AS total_allocated, '
                . 'ar.id AS ar_id, ar.activity_title, ar.control_number, ar.act_design_id, '
                . 'ar.start_date, ar.male, ar.female, ar.status'
            )
            ->join('accomplishment_budget_items abi', 'abi.id = bima.budget_item_id')
            ->join('accomplishment_report ar', 'ar.id = abi.accomplishment_report_id')
            ->where('bima.item_type', 'AR')
            ->whereIn('bima.mandate_id', $allLineageIds)
            ->where('bima.allocated_amount >', 0)
            ->where('ar.deleted_at', null)
            ->whereIn('ar.status', ['Verified', 'Pending', 'Revision Required'])
            ->groupBy('bima.mandate_id, ar.id, bima.gpb_budget_line_id')
            ->get()
            ->getResultArray();

        // 4. Batch-query AR -> GPB links via activity_design_mandates (+ activity_design_issues)
        //    so unallocated / Pending ARs also appear under their linked GPB item.
        //    Join by control_number OR act_design_id for resilience.
        $linkedArRows = $this->db->query(
            "SELECT DISTINCT
                adm.mandate_id,
                ar.id AS ar_id,
                ar.activity_title,
                ar.control_number,
                ar.act_design_id,
                ar.start_date,
                ar.male,
                ar.female,
                ar.status
             FROM activity_design_mandates adm
             INNER JOIN activity_design ad
                ON ad.act_design_id = adm.act_design_id
               AND ad.deleted_at IS NULL
             LEFT JOIN activity_design_issues adi
                ON adi.act_design_id = ad.act_design_id
               AND adi.issue_id = adm.mandate_id
             INNER JOIN accomplishment_report ar
                ON (
                    (ar.control_number IS NOT NULL AND ar.control_number <> '' AND ar.control_number = ad.control_number)
                    OR (ar.act_design_id IS NOT NULL AND ar.act_design_id = ad.act_design_id)
                )
               AND ar.deleted_at IS NULL
               AND ar.status IN ('Verified', 'Pending', 'Revision Required')
             WHERE adm.mandate_id IN (" . implode(',', array_map('intval', $allLineageIds)) . ")
               AND (
                    adi.id IS NOT NULL
                    OR NOT EXISTS (
                        SELECT 1 FROM activity_design_issues adi2
                        WHERE adi2.act_design_id = ad.act_design_id
                    )
               )"
        )->getResultArray();

        // Fallback start_date from accomplishment_schedules if any AR has empty/zero start_date
        $arIdsNeedingSchedule = [];
        foreach (array_merge($allocRows, $linkedArRows) as $r) {
            $sd = trim((string) ($r['start_date'] ?? ''));
            if ($sd === '' || $sd === '0000-00-00') {
                $arIdsNeedingSchedule[(int) $r['ar_id']] = (int) $r['ar_id'];
            }
        }
        $fallbackDates = [];
        if (!empty($arIdsNeedingSchedule) && $this->db->tableExists('accomplishment_schedules')) {
            $schedRows = $this->db->table('accomplishment_schedules')
                ->select('accomplishment_report_id, MIN(start_date) AS min_date')
                ->whereIn('accomplishment_report_id', array_values($arIdsNeedingSchedule))
                ->groupBy('accomplishment_report_id')
                ->get()
                ->getResultArray();
            foreach ($schedRows as $sr) {
                $fallbackDates[(int) $sr['accomplishment_report_id']] = (string) ($sr['min_date'] ?? '');
            }
        }

        // Organize ARs per planEntry: $entryArs[$eKey][$arId] = ['meta' => ..., 'allocs' => [lineKey => amount]]
        $entryArs = [];

        foreach ($linkedArRows as $row) {
            $mId = (int) $row['mandate_id'];
            $arId = (int) $row['ar_id'];
            foreach ($mandateIdToEntryKeys[$mId] ?? [] as $eKey) {
                if (!isset($entryArs[$eKey][$arId])) {
                    $entryArs[$eKey][$arId] = [
                        'meta'   => $this->extractArMeta($row, $fallbackDates),
                        'allocs' => [],
                    ];
                }
            }
        }

        foreach ($allocRows as $row) {
            $mId    = (int) $row['mandate_id'];
            $arId   = (int) $row['ar_id'];
            $lineId = trim((string) ($row['gpb_budget_line_id'] ?? ''));
            $amt    = (float) ($row['total_allocated'] ?? 0);
            if ($amt <= 0) {
                continue;
            }

            foreach ($mandateIdToEntryKeys[$mId] ?? [] as $eKey) {
                if (!isset($entryArs[$eKey][$arId])) {
                    $entryArs[$eKey][$arId] = [
                        'meta'   => $this->extractArMeta($row, $fallbackDates),
                        'allocs' => [],
                    ];
                }
                $resolvedLineKey = $this->resolveBudgetLineKey(
                    $lineId,
                    $planEntries[$eKey]['budget_lines'],
                    $historicalLinesById
                );
                $entryArs[$eKey][$arId]['allocs'][$resolvedLineKey] =
                    ($entryArs[$eKey][$arId]['allocs'][$resolvedLineKey] ?? 0.0) + $amt;
            }
        }

        // 5. Build Layout A row blocks per GPB plan entry.
        foreach ($planEntries as $eKey => $entry) {
            $section     = $entry['section'];
            $budgetLines = $entry['budget_lines']; // ordered map [lineKey => ['id','label','amount','source']]
            $ars         = array_values($entryArs[$eKey] ?? []);

            // Sort ARs chronologically by start_date ASC, then ar_id ASC
            usort($ars, static function (array $a, array $b): int {
                $da = $a['meta']['start_date'] ?: '9999-12-31';
                $db = $b['meta']['start_date'] ?: '9999-12-31';
                if ($da !== $db) {
                    return strcmp($da, $db);
                }
                return $a['meta']['id'] <=> $b['meta']['id'];
            });

            // Determine which GPB budget lines are used by at least one Verified + Allocated AR
            $usedLineKeys = [];
            foreach ($ars as $arBundle) {
                if ($arBundle['meta']['is_verified'] && !empty($arBundle['allocs'])) {
                    foreach ($arBundle['allocs'] as $lk => $amt) {
                        if ($amt > 0 && isset($budgetLines[$lk])) {
                            $usedLineKeys[$lk] = true;
                        }
                    }
                }
            }

            // Collect unused GPB budget lines (appear on the GPB Plan row for reference, AH=0)
            $unusedLines = [];
            foreach ($budgetLines as $lk => $bl) {
                if (!isset($usedLineKeys[$lk])) {
                    $unusedLines[] = $bl;
                }
            }

            // 5a. Emit GPB Plan Row (+ continuation rows if >1 unused line)
            $firstUnused = $unusedLines[0] ?? null;
            $grouped[$section][] = [
                'mandate'     => $entry['mandate'],
                'activity'    => $entry['activity'],
                'indicators'  => $entry['indicators'],
                'male'        => null,
                'female'      => null,
                'budget_line' => $firstUnused ? $firstUnused['label'] : null,
                'month_date'  => null,
                'ar_status'   => null,
                'allocation'  => null,
                'allocated'   => $firstUnused ? (float) $firstUnused['amount'] : null,
                'actual_exp'  => null,
                'attrib_gaa'  => null,
            ];

            for ($i = 1, $uCount = count($unusedLines); $i < $uCount; $i++) {
                $ul = $unusedLines[$i];
                $grouped[$section][] = [
                    'mandate'     => null,
                    'activity'    => null,
                    'indicators'  => null,
                    'male'        => null,
                    'female'      => null,
                    'budget_line' => $ul['label'],
                    'month_date'  => null,
                    'ar_status'   => null,
                    'allocation'  => null,
                    'allocated'   => (float) $ul['amount'],
                    'actual_exp'  => null,
                    'attrib_gaa'  => null,
                ];
            }

            // 5b. Emit AR rows directly below the GPB Plan row
            // Track which budget lines have already had their planned amount emitted in L (#37)
            $plannedEmittedForLine = [];

            foreach ($ars as $arBundle) {
                $meta   = $arBundle['meta'];
                $allocs = $arBundle['allocs'];

                // Pending / Revision Required ARs -> listed as Pending + Not Yet Allocated with blank amounts
                if (!$meta['is_verified']) {
                    $grouped[$section][] = [
                        'mandate'     => null,
                        'activity'    => $meta['title'],
                        'indicators'  => null,
                        'male'        => $meta['male'],
                        'female'      => $meta['female'],
                        'budget_line' => null,
                        'month_date'  => $meta['start_date'],
                        'ar_status'   => 'Pending',
                        'allocation'  => 'Not Yet Allocated',
                        'allocated'   => null,
                        'actual_exp'  => null,
                        'attrib_gaa'  => null,
                    ];
                    continue;
                }

                // Verified AR without allocations yet -> Verified + Not Yet Allocated with blank amounts
                if (empty($allocs)) {
                    $grouped[$section][] = [
                        'mandate'     => null,
                        'activity'    => $meta['title'],
                        'indicators'  => null,
                        'male'        => $meta['male'],
                        'female'      => $meta['female'],
                        'budget_line' => null,
                        'month_date'  => $meta['start_date'],
                        'ar_status'   => 'Verified',
                        'allocation'  => 'Not Yet Allocated',
                        'allocated'   => null,
                        'actual_exp'  => null,
                        'attrib_gaa'  => null,
                    ];
                    continue;
                }

                // Verified + Allocated AR -> order its allocated lines according to GPB budget_lines order
                $orderedAllocLines = [];
                foreach ($budgetLines as $lk => $bl) {
                    if (isset($allocs[$lk]) && $allocs[$lk] > 0) {
                        $orderedAllocLines[] = [
                            'key'           => $lk,
                            'label'         => $bl['label'],
                            'planned'       => (float) $bl['amount'],
                            'has_planned'   => true,
                            'source'        => $bl['source'],
                            'allocated_amt' => (float) $allocs[$lk],
                        ];
                    }
                }
                // Include any historical/orphan line key not in current $budgetLines
                foreach ($allocs as $lk => $amt) {
                    if ($amt > 0 && !isset($budgetLines[$lk])) {
                        $hist = $historicalLinesById[$lk] ?? null;
                        $orderedAllocLines[] = [
                            'key'           => $lk,
                            'label'         => $hist['label'] ?? '',
                            'planned'       => isset($hist['amount']) ? (float) $hist['amount'] : 0.0,
                            'has_planned'   => isset($hist['amount']),
                            'source'        => $hist['source'] ?? $entry['source'],
                            'allocated_amt' => (float) $amt,
                        ];
                    }
                }

                foreach ($orderedAllocLines as $idx => $alLine) {
                    $lk = $alLine['key'];
                    $plannedCellVal = null;
                    if ($alLine['has_planned'] && !isset($plannedEmittedForLine[$lk])) {
                        $plannedCellVal = $alLine['planned'];
                        $plannedEmittedForLine[$lk] = true;
                    }

                    $isGaa = strtoupper(trim((string) $alLine['source'])) === 'GAA';
                    $actualExp = $isGaa ? 0.0 : $alLine['allocated_amt'];
                    $attribGaa = $isGaa ? $alLine['allocated_amt'] : 0.0;

                    if ($idx === 0) {
                        $grouped[$section][] = [
                            'mandate'     => null,
                            'activity'    => $meta['title'],
                            'indicators'  => null,
                            'male'        => $meta['male'],
                            'female'      => $meta['female'],
                            'budget_line' => $alLine['label'],
                            'month_date'  => $meta['start_date'],
                            'ar_status'   => 'Verified',
                            'allocation'  => 'Allocated',
                            'allocated'   => $plannedCellVal,
                            'actual_exp'  => $actualExp,
                            'attrib_gaa'  => $attribGaa,
                        ];
                    } else {
                        // Continuation row: C, E, F, I, J, K blank (AE:AG inherit from row above)
                        $grouped[$section][] = [
                            'mandate'     => null,
                            'activity'    => null,
                            'indicators'  => null,
                            'male'        => null,
                            'female'      => null,
                            'budget_line' => $alLine['label'],
                            'month_date'  => null,
                            'ar_status'   => null,
                            'allocation'  => null,
                            'allocated'   => $plannedCellVal,
                            'actual_exp'  => $actualExp,
                            'attrib_gaa'  => $attribGaa,
                        ];
                    }
                }
            }
        }

        return $grouped;
    }

    /**
     * @param array<string, mixed> $row
     * @param array<int, string>   $fallbackDates
     * @return array{id: int, title: string, start_date: ?string, male: int, female: int, is_verified: bool}
     */
    private function extractArMeta(array $row, array $fallbackDates): array
    {
        $arId = (int) $row['ar_id'];
        $sd   = trim((string) ($row['start_date'] ?? ''));
        if ($sd === '' || $sd === '0000-00-00') {
            $sd = trim((string) ($fallbackDates[$arId] ?? ''));
        }
        if ($sd === '' || $sd === '0000-00-00') {
            $sd = null;
        }

        $status = trim((string) ($row['status'] ?? ''));

        return [
            'id'          => $arId,
            'title'       => trim((string) ($row['activity_title'] ?? '')),
            'start_date'  => $sd,
            'male'        => (int) ($row['male'] ?? 0),
            'female'      => (int) ($row['female'] ?? 0),
            'is_verified' => strcasecmp($status, 'Verified') === 0,
        ];
    }

    /**
     * Match an allocation's gpb_budget_line_id to the current GPB item's budget lines,
     * falling back to label match via historical version lines if line IDs changed.
     *
     * @param array<string, array{id: string, label: string, amount: float, source: string}> $currentLines
     * @param array<string, array{label: string, amount: float, source: string}>             $historicalLinesById
     */
    private function resolveBudgetLineKey(string $lineId, array $currentLines, array $historicalLinesById): string
    {
        if ($lineId !== '' && isset($currentLines[$lineId])) {
            return $lineId;
        }

        if ($lineId !== '' && isset($historicalLinesById[$lineId])) {
            $histLabel = strtolower(trim($historicalLinesById[$lineId]['label']));
            if ($histLabel !== '') {
                foreach ($currentLines as $ck => $cl) {
                    if (strtolower(trim($cl['label'])) === $histLabel) {
                        return $ck;
                    }
                }
            }
            return $lineId;
        }

        if (count($currentLines) === 1) {
            return (string) array_key_first($currentLines);
        }

        return $lineId !== '' ? $lineId : '__unassigned';
    }
}

