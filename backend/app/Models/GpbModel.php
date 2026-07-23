<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * GpbModel
 *
 * Expected table: gpb_items
 *   id             INT PK
 *   fiscal_year    INT (e.g. 2026)
 *   section        ENUM('client_focused','organization_focused','attributed_program')
 *   sort_order     INT            -- controls order within a section
 *   mandate        TEXT           -- Gender Issue / GAD Mandate
 *   cause          TEXT           -- Cause of Gender Issue
 *   objective      TEXT           -- GAD Result Statement / GAD Objective
 *   ppa            TEXT           -- Relevant Organization MFO/PAP or PPA
 *   activity       TEXT           -- GAD Activity
 *   targets        TEXT           -- Performance Indicators / Targets
 *   budget         DECIMAL(15,2)  -- GAD Budget
 *   source         VARCHAR(50)    -- Source of Budget (GAA, OSS, etc.)
 *   office         VARCHAR(255)   -- Responsible Unit / Office
 */
class GpbModel extends Model
{
    protected $table            = 'gpb_items';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'fiscal_year', 'section', 'sort_order', 'mandate', 'cause',
        'objective', 'ppa', 'activity', 'targets', 'budget', 'source', 'office',
        'result', 'mfo', 'indicators', 'responsible', 'budget_lines',
    ];
    protected $useTimestamps    = true;

    /** Sections in the exact top-to-bottom order they appear in the template. */
    public const SECTION_ORDER = ['client_focused', 'organization_focused', 'attributed_program'];

    /**
     * Fetch every GPB row for a fiscal year in a single query, then group it
     * in PHP. One round trip is enough for a report this size, and it keeps
     * the section ordering guaranteed regardless of how MySQL returns rows.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function getGroupedBySection(int $fiscalYear): array
    {
        $rows = $this->where('fiscal_year', $fiscalYear)
                     ->orderBy('section', 'ASC')
                     ->orderBy('sort_order', 'ASC')
                     ->orderBy('id', 'ASC')
                     ->findAll();

        $grouped = array_fill_keys(self::SECTION_ORDER, []);

        foreach ($rows as $row) {
            if (isset($grouped[$row['section']])) {
                $grouped[$row['section']][] = $row;
            }
        }

        return $grouped;
    }
}
