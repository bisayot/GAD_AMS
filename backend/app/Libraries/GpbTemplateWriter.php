<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * GpbTemplateWriter
 *
 * All the "how do I populate gpb_template.xlsx without breaking its layout"
 * logic lives here, shared by:
 *   - GpbExportController      (reads MySQL, one fiscal year at a time)
 *   - GpbLiveExportController  (reads a JSON payload posted straight from
 *                                the browser-based GAD Plan editor)
 *
 * Both callers normalize their data into the same shape before calling
 * build(), so this class never needs to know where the data came from.
 *
 * Expected item shape per row:
 *   [
 *     'mandate'        => string,
 *     'cause'          => string,
 *     'objective'      => string,
 *     'ppa'            => string,
 *     'activity'       => string,
 *     'targets'        => string,
 *     'budget_value'   => float,        // always required — used for totals
 *     'budget_display' => ?string,      // optional multi-line override text
 *     'source'         => string,       // may contain "\n" for stacked lines
 *     'office'         => string,
 *   ]
 *
 * Expected $grouped shape: ['client_focused' => [...items], 'organization_focused' => [...], 'attributed_program' => [...]]
 */
class GpbTemplateWriter
{
    public const TEMPLATE_PATH = APPPATH . 'Assets/templates/gpb_template.xlsx';
    public const SHEET_NAME    = 'Table 1';

    /**
     * Row 10 holds the static "1 2 3 4 5 6 7 8 9" column-reference row and is
     * never written to. Each section has exactly one pre-styled blank row
     * reserved for it, immediately followed by the next section's header.
     */
    private const SECTION_ANCHORS = [
        'client_focused'       => ['header_row' => 11, 'first_data_row' => 12],
        'organization_focused' => ['header_row' => 13, 'first_data_row' => 14],
        'attributed_program'   => ['header_row' => 15, 'first_data_row' => 16],
    ];

    private const SUBTOTAL_ROW_BASE = 17;
    private const TOTAL_ROW_BASE    = 18;

    /** Field => column letter, pointing at the top-left cell of each header's merge range. */
    private const COLUMNS = [
        'mandate'   => 'B',
        'cause'     => 'C', // merged C:D
        'objective' => 'E', // merged E:G
        'ppa'       => 'H', // merged H:I
        'activity'  => 'J',
        'targets'   => 'K',
        'source'    => 'N',
        'office'    => 'O',
    ];
    private const BUDGET_COLUMN = 'L'; // merged L:M, handled separately (numeric vs display text)

    private const ROW_COLUMNS = ['A', 'B', 'C', 'E', 'H', 'J', 'K', 'L', 'N', 'O'];
    private const ROW_MERGES  = ['C:D', 'E:G', 'H:I', 'L:M'];

    public const SECTION_ORDER = ['client_focused', 'organization_focused', 'attributed_program'];

    /**
     * @param array<string, array<int, array<string, mixed>>> $grouped
     * @param array{
     *   org_name?: string, org_category?: string, org_hierarchy?: string,
     *   fiscal_year?: string, total_org_budget?: float, other_sources?: float
     * } $headerMeta
     */
    public function build(array $grouped, array $headerMeta = []): Spreadsheet
    {
        $spreadsheet = IOFactory::load(self::TEMPLATE_PATH);
        $sheet       = $spreadsheet->getSheetByName(self::SHEET_NAME) ?? $spreadsheet->getActiveSheet();

        // Pass 1 (bottom-up): insert rows for any section with more than one
        // item. Going bottom-up means the anchor row numbers for sections
        // still above the current one are never disturbed mid-loop.
        foreach (array_reverse(self::SECTION_ORDER) as $section) {
            $n = count($grouped[$section] ?? []);
            if ($n <= 1) {
                continue;
            }
            $anchorRow = self::SECTION_ANCHORS[$section]['first_data_row'];
            $sheet->insertNewRowBefore($anchorRow + 1, $n - 1);
            for ($i = 1; $i < $n; $i++) {
                $this->cloneRowStyle($sheet, $anchorRow, $anchorRow + $i);
            }
        }

        // Pass 2 (top-down): write values, tracking how far later sections
        // have shifted because of what pass 1 inserted above them.
        $itemCounter = 0;
        $grandTotal  = 0.0;
        $shift       = 0;

        foreach (self::SECTION_ORDER as $section) {
            $rows      = $grouped[$section] ?? [];
            $n         = count($rows);
            $anchorRow = self::SECTION_ANCHORS[$section]['first_data_row'] + $shift;

            foreach ($rows as $offset => $item) {
                $rowIndex = $anchorRow + $offset;
                $itemCounter++;
                $grandTotal += (float) ($item['budget_value'] ?? 0);
                $this->writeRow($sheet, $rowIndex, $itemCounter, $item);
            }

            if ($n > 1) {
                $shift += $n - 1;
            }
        }

        $this->writeSummary($sheet, $shift, $grandTotal);

        if (! empty($headerMeta)) {
            $this->writeHeader($sheet, $headerMeta, $grandTotal);
        }

        return $spreadsheet;
    }

    private function writeRow(Worksheet $sheet, int $rowIndex, int $itemNumber, array $item): void
    {
        $sheet->setCellValue("A{$rowIndex}", $itemNumber);

        foreach (self::COLUMNS as $field => $column) {
            $sheet->setCellValue("{$column}{$rowIndex}", (string) ($item[$field] ?? ''));
        }

        $budgetCell = self::BUDGET_COLUMN . $rowIndex;
        if (! empty($item['budget_display'])) {
            // Multi-line breakdown (e.g. several budget lines stacked with
            // "\n") — write as text, not a formatted number.
            $sheet->setCellValue($budgetCell, (string) $item['budget_display']);
            $sheet->getStyle($budgetCell)->getNumberFormat()->setFormatCode('General');
        } else {
            $sheet->setCellValue($budgetCell, (float) ($item['budget_value'] ?? 0));
            $sheet->getStyle($budgetCell)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        foreach (self::ROW_COLUMNS as $column) {
            $style = $sheet->getStyle("{$column}{$rowIndex}");
            $style->getAlignment()->setWrapText(true);
            $style->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        }
    }

    private function cloneRowStyle(Worksheet $sheet, int $fromRow, int $toRow): void
    {
        foreach (self::ROW_COLUMNS as $column) {
            $styleArray = $sheet->getStyle("{$column}{$fromRow}")->exportArray();
            $sheet->getStyle("{$column}{$toRow}")->applyFromArray($styleArray);
        }

        $sheet->getRowDimension($toRow)->setRowHeight(
            $sheet->getRowDimension($fromRow)->getRowHeight()
        );

        foreach (self::ROW_MERGES as $merge) {
            [$start, $end] = explode(':', $merge);
            $sheet->mergeCells("{$start}{$toRow}:{$end}{$toRow}");
        }
    }

    private function writeSummary(Worksheet $sheet, int $totalShift, float $grandTotal): void
    {
        foreach ([self::SUBTOTAL_ROW_BASE, self::TOTAL_ROW_BASE] as $baseRow) {
            $row = $baseRow + $totalShift;
            $sheet->setCellValue("L{$row}", $grandTotal);
            $sheet->getStyle("L{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        }
    }

    /** Populates the profile/ledger block at the top of the sheet (rows 2-7). */
    private function writeHeader(Worksheet $sheet, array $meta, float $grandTotal): void
    {
        $totalOrgBudget = (float) ($meta['total_org_budget'] ?? 0);
        $otherSources   = (float) ($meta['other_sources'] ?? 0);
        $primarySources = $grandTotal - $otherSources;
        $pct            = $totalOrgBudget > 0 ? ($grandTotal / $totalOrgBudget * 100) : 0;

        if (! empty($meta['org_name'])) {
            $sheet->setCellValue('A2', 'Organization: ' . $meta['org_name']);
        }
        if (! empty($meta['org_category'])) {
            $sheet->setCellValue('I2', 'Organization Category: ' . $meta['org_category']);
        }
        if (! empty($meta['org_hierarchy'])) {
            $sheet->setCellValue('A3', 'Organization Hierarchy: ' . $meta['org_hierarchy']);
        }

        $sheet->setCellValue('D4', $totalOrgBudget);
        $sheet->getStyle('D4')->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->setCellValue('C5', $grandTotal);
        $sheet->setCellValue('F5', $primarySources);
        $sheet->setCellValue('F6', $otherSources);
        $sheet->getStyle('F6')->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->setCellValue('D7', number_format($pct, 2) . '%');

        // Optional: swap the trailing "FY XXXX" in the title if the payload
        // provides a fiscal year in that exact format. Left untouched otherwise.
        if (! empty($meta['fiscal_year'])) {
            $title = (string) $sheet->getCell('A1')->getValue();
            $swapped = preg_replace('/FY\s*\d{4}$/', $meta['fiscal_year'], $title, 1, $count);
            if ($count > 0) {
                $sheet->setCellValue('A1', $swapped);
            }
        }
    }
}
