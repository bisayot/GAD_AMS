<?php

namespace App\Libraries;

use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * GadArTemplateWriter
 *
 * Populates gad_ar_budget_template.xlsx ("GAD AR" sheet) with AR-based GAD
 * Budget Utilization rows, automatically expanding any section on the same
 * sheet when its row count exceeds the template's default capacity, rebuilding
 * all summary/check formulas, conditional formatting ranges, and print area,
 * and stripping all data-validation dropdowns.
 */
class GadArTemplateWriter
{
    public const TEMPLATE_PATH = APPPATH . 'Assets/templates/gad_ar_budget_template.xlsx';
    public const SHEET_NAME    = 'GAD AR';

    public const SECTION_ORDER = [
        'client_focused',
        'organization_focused',
        'attributed_program',
    ];

    /**
     * Base coordinates in the clean template (before any overflow insertions).
     */
    private const BASE_SECTIONS = [
        'client_focused' => [
            'header_row' => 12,
            'start_row'  => 13,
            'end_row'    => 52,
            'capacity'   => 40,
            'sub_row'    => 53,
        ],
        'organization_focused' => [
            'header_row' => 54,
            'start_row'  => 55,
            'end_row'    => 114,
            'capacity'   => 60,
            'sub_row'    => 115,
        ],
        'attributed_program' => [
            'header_row' => 116,
            'start_row'  => 117,
            'end_row'    => 131,
            'capacity'   => 15,
            'sub_row'    => 132,
        ],
    ];

    private const BASE_TOTAL_ROW        = 133;
    private const BASE_SUMMARY_HDR_ROW  = 137;
    private const BASE_SUMMARY_ALLOC    = 138;
    private const BASE_SUMMARY_ACTUAL   = 139;
    private const BASE_SUMMARY_ATTRIB   = 140;
    private const BASE_SUMMARY_TOTCOST  = 141;
    private const BASE_SUMMARY_BALANCE  = 142;
    private const BASE_SUMMARY_ACTCNT   = 143;
    private const BASE_SUMMARY_MALE     = 144;
    private const BASE_SUMMARY_FEMALE   = 145;
    private const BASE_CHECK_EXCLUDED   = 148;
    private const BASE_CHECK_OUTSIDE_FY = 149;

    private const MONTH_COLS = ['Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB'];

    /**
     * @param array<string, array<int, array<string, mixed>>> $grouped
     */
    public function build(array $grouped, int $fiscalYear): Spreadsheet
    {
        $spreadsheet = IOFactory::load(self::TEMPLATE_PATH);
        $sheet       = $spreadsheet->getSheetByName(self::SHEET_NAME) ?? $spreadsheet->getActiveSheet();

        // Set Fiscal Year in C2 (drives Q10:AB10 monthly dates)
        $sheet->setCellValueExplicit('C2', $fiscalYear, DataType::TYPE_NUMERIC);

        // Remove all data-validation dropdowns up front (decision 7/15) and
        // remove base conditional styles before inserting rows so we can attach
        // exact shifted conditional ranges cleanly afterward.
        $this->clearAllDataValidations($sheet);
        foreach (array_keys($sheet->getConditionalStylesCollection()) as $cfRange) {
            $sheet->removeConditionalStyles($cfRange);
        }

        // Compute overflow per section
        $extras = [];
        foreach (self::SECTION_ORDER as $sec) {
            $needed       = count($grouped[$sec] ?? []);
            $capacity     = self::BASE_SECTIONS[$sec]['capacity'];
            $extras[$sec] = max(0, $needed - $capacity);
        }

        // Pass 1 (bottom-up): insert overflow rows strictly inside each section
        // (before its last data row) so upper section coordinates stay intact.
        foreach (array_reverse(self::SECTION_ORDER) as $sec) {
            $extra = $extras[$sec];
            if ($extra <= 0) {
                continue;
            }
            $baseStart = self::BASE_SECTIONS[$sec]['start_row'];
            $baseEnd   = self::BASE_SECTIONS[$sec]['end_row'];

            // Insert before $baseEnd so new rows occupy [$baseEnd .. $baseEnd + $extra - 1]
            $sheet->insertNewRowBefore($baseEnd, $extra);

            // Clone cell xfIndex styles across A..AH from the template's first data row
            $maxColIdx = Coordinate::columnIndexFromString('AH');
            for ($colIdx = 1; $colIdx <= $maxColIdx; $colIdx++) {
                $col = Coordinate::stringFromColumnIndex($colIdx);
                $sheet->duplicateStyle(
                    $sheet->getStyle("{$col}{$baseStart}"),
                    "{$col}{$baseEnd}:{$col}" . ($baseEnd + $extra - 1)
                );
            }
            for ($r = $baseEnd; $r < $baseEnd + $extra; $r++) {
                $sheet->getRowDimension($r)->setRowHeight(15);
            }
        }

        // Compute final shifted bounds for each section
        $bounds     = [];
        $runningAdd = 0;
        foreach (self::SECTION_ORDER as $sec) {
            $base    = self::BASE_SECTIONS[$sec];
            $extra   = $extras[$sec];
            $start   = $base['start_row'] + $runningAdd;
            $end     = $base['end_row'] + $runningAdd + $extra;
            $subRow  = $base['sub_row'] + $runningAdd + $extra;

            $bounds[$sec] = [
                'start'   => $start,
                'end'     => $end,
                'sub_row' => $subRow,
            ];

            $runningAdd += $extra;
        }
        $totalShift = $runningAdd;
        $totalRow   = self::BASE_TOTAL_ROW + $totalShift;
        $cStart     = $bounds['client_focused']['start'];

        // Pass 2: Format Column I as full month + year ("March 2026") and adjust
        // Column I / K widths slightly so dates never display "##########".
        $sheet->getColumnDimension('I')->setWidth(16);
        $sheet->getColumnDimension('K')->setWidth(18);

        // Pass 3: Rewrite per-row formulas across every data row in all 3 sections
        // (with AH checking AF="Verified", decision 28) and apply conditional styles.
        foreach (self::SECTION_ORDER as $sec) {
            $b = $bounds[$sec];
            $sheet->getStyle("I{$b['start']}:I{$b['end']}")
                ->getNumberFormat()
                ->setFormatCode('mmmm yyyy');

            for ($r = $b['start']; $r <= $b['end']; $r++) {
                $this->writeRowFormulas($sheet, $r, $cStart);
            }

            $this->applySectionConditionalFormatting($sheet, $b['start'], $b['end']);
        }

        // Pass 4: Deterministically rebuild subtotals, TOTAL GAD COST,
        // MONTHLY BUDGET SUMMARY, Data Checks, and Print Area.
        foreach (self::SECTION_ORDER as $sec) {
            $b = $bounds[$sec];
            $this->writeSubtotalFormulas($sheet, $b['sub_row'], $b['start'], $b['end']);
        }

        $this->writeTotalRowFormulas(
            $sheet,
            $totalRow,
            $bounds['client_focused']['sub_row'],
            $bounds['organization_focused']['sub_row'],
            $bounds['attributed_program']['sub_row']
        );

        $this->writeSummaryAndCheckFormulas($sheet, $bounds, $totalShift, $totalRow);

        $sheet->getPageSetup()->setPrintArea("A1:P{$totalRow}");

        // Pass 5: Populate input cells top-down without touching formula columns.
        foreach (self::SECTION_ORDER as $sec) {
            $rows  = $grouped[$sec] ?? [];
            $start = $bounds[$sec]['start'];

            foreach ($rows as $offset => $rowSpec) {
                $this->writeDataRow($sheet, $start + $offset, $rowSpec);
            }
        }

        // Ensure zero data validations remain anywhere on the sheet.
        $this->clearAllDataValidations($sheet);

        return $spreadsheet;
    }

    /**
     * Write input columns (B, C, D, E, F, H, I, J, K, L, M, N) for one row.
     *
     * @param array<string, mixed> $rowSpec
     */
    private function writeDataRow(Worksheet $sheet, int $r, array $rowSpec): void
    {
        $hasContent = false;

        $textCols = [
            'B' => $rowSpec['mandate'] ?? null,
            'C' => $rowSpec['activity'] ?? null,
            'D' => $rowSpec['indicators'] ?? null,
            'H' => $rowSpec['budget_line'] ?? null,
            'J' => $rowSpec['ar_status'] ?? null,
            'K' => $rowSpec['allocation'] ?? null,
        ];

        foreach ($textCols as $col => $val) {
            $str = trim((string) ($val ?? ''));
            if ($str !== '') {
                $sheet->setCellValueExplicit("{$col}{$r}", $str, DataType::TYPE_STRING);
                $hasContent = true;
            }
        }

        if ($rowSpec['male'] !== null && $rowSpec['male'] !== '') {
            $sheet->setCellValueExplicit("E{$r}", (int) $rowSpec['male'], DataType::TYPE_NUMERIC);
            $hasContent = true;
        }
        if ($rowSpec['female'] !== null && $rowSpec['female'] !== '') {
            $sheet->setCellValueExplicit("F{$r}", (int) $rowSpec['female'], DataType::TYPE_NUMERIC);
            $hasContent = true;
        }

        $dateStr = trim((string) ($rowSpec['month_date'] ?? ''));
        if ($dateStr !== '') {
            try {
                $dt = new DateTimeImmutable(substr($dateStr, 0, 10) . ' 00:00:00');
                $sheet->setCellValueExplicit("I{$r}", ExcelDate::PHPToExcel($dt), DataType::TYPE_NUMERIC);
                $hasContent = true;
            } catch (\Throwable) {
                // Leave I blank if date string is malformed
            }
        }

        $numCols = [
            'L' => $rowSpec['allocated'] ?? null,
            'M' => $rowSpec['actual_exp'] ?? null,
            'N' => $rowSpec['attrib_gaa'] ?? null,
        ];

        foreach ($numCols as $col => $val) {
            if ($val !== null && $val !== '') {
                $sheet->setCellValueExplicit("{$col}{$r}", round((float) $val, 2), DataType::TYPE_NUMERIC);
                $hasContent = true;
            }
        }

        if ($hasContent) {
            // Auto-fit row height in Excel so wrapped multi-line text is never clipped
            $sheet->getRowDimension($r)->setRowHeight(-1);
        }
    }

    private function writeRowFormulas(Worksheet $sheet, int $r, int $clientFirstRow): void
    {
        $prev = $r - 1;

        $sheet->setCellValue("A{$r}", "=IF(C{$r}=\"\",\"\",COUNTIF(\$C\${$clientFirstRow}:C{$r},\"?*\"))");
        $sheet->setCellValue("G{$r}", "=IF(AND(E{$r}=\"\",F{$r}=\"\"),\"\",N(E{$r})+N(F{$r}))");
        $sheet->setCellValue("O{$r}", "=IF(AND(L{$r}=\"\",M{$r}=\"\",N{$r}=\"\"),\"\",N(M{$r})+N(N{$r}))");
        $sheet->setCellValue("P{$r}", "=IF(O{$r}=\"\",\"\",N(L{$r})-O{$r})");

        foreach (self::MONTH_COLS as $mc) {
            $sheet->setCellValue(
                "{$mc}{$r}",
                "=IF(OR(AH{$r}=0,AE{$r}=\"\"),\"\",IF(YEAR(AE{$r})*12+MONTH(AE{$r})=YEAR({$mc}\$10)*12+MONTH({$mc}\$10),O{$r},\"\"))"
            );
        }

        $sheet->setCellValue("AC{$r}", "=IF(O{$r}=\"\",\"\",SUM(Q{$r}:AB{$r}))");
        $sheet->setCellValue("AE{$r}", "=IF(I{$r}<>\"\",I{$r},IF(C{$r}=\"\",IF(AE{$prev}=\"\",\"\",AE{$prev}),\"\"))");
        $sheet->setCellValue("AF{$r}", "=IF(J{$r}<>\"\",J{$r},IF(C{$r}=\"\",AF{$prev}&\"\",\"\"))");
        $sheet->setCellValue("AG{$r}", "=IF(K{$r}<>\"\",K{$r},IF(C{$r}=\"\",AG{$prev}&\"\",\"\"))");
        $sheet->setCellValue("AH{$r}", "=IF(AND(O{$r}<>\"\",AF{$r}=\"Verified\",AG{$r}=\"Allocated\"),1,0)");
    }

    private function writeSubtotalFormulas(Worksheet $sheet, int $subRow, int $start, int $end): void
    {
        foreach (['E', 'F', 'L', 'M', 'N', 'O'] as $col) {
            $sheet->setCellValue(
                "{$col}{$subRow}",
                "=SUMIFS(\${$col}\${$start}:\${$col}\${$end},\$AH\${$start}:\$AH\${$end},1)"
            );
        }

        $sheet->setCellValue("G{$subRow}", "=E{$subRow}+F{$subRow}");
        $sheet->setCellValue("P{$subRow}", "=L{$subRow}-O{$subRow}");

        foreach (self::MONTH_COLS as $mc) {
            $sheet->setCellValue("{$mc}{$subRow}", "=SUM({$mc}{$start}:{$mc}{$end})");
        }

        $sheet->setCellValue("AC{$subRow}", "=SUM(Q{$subRow}:AB{$subRow})");
    }

    private function writeTotalRowFormulas(Worksheet $sheet, int $totalRow, int $cSub, int $oSub, int $aSub): void
    {
        $cols = array_merge(['E', 'F', 'G', 'L', 'M', 'N', 'O', 'P'], self::MONTH_COLS, ['AC']);
        foreach ($cols as $col) {
            $sheet->setCellValue("{$col}{$totalRow}", "={$col}{$cSub}+{$col}{$oSub}+{$col}{$aSub}");
        }
    }

    /**
     * @param array<string, array{start: int, end: int, sub_row: int}> $bounds
     */
    private function writeSummaryAndCheckFormulas(Worksheet $sheet, array $bounds, int $totalShift, int $totalRow): void
    {
        $cS = $bounds['client_focused']['start'];
        $cE = $bounds['client_focused']['end'];
        $oS = $bounds['organization_focused']['start'];
        $oE = $bounds['organization_focused']['end'];
        $aS = $bounds['attributed_program']['start'];
        $aE = $bounds['attributed_program']['end'];

        $rHdr    = self::BASE_SUMMARY_HDR_ROW + $totalShift;
        $rAlloc  = self::BASE_SUMMARY_ALLOC + $totalShift;
        $rActual = self::BASE_SUMMARY_ACTUAL + $totalShift;
        $rAttrib = self::BASE_SUMMARY_ATTRIB + $totalShift;
        $rTot    = self::BASE_SUMMARY_TOTCOST + $totalShift;
        $rBal    = self::BASE_SUMMARY_BALANCE + $totalShift;
        $rActCnt = self::BASE_SUMMARY_ACTCNT + $totalShift;
        $rMale   = self::BASE_SUMMARY_MALE + $totalShift;
        $rFemale = self::BASE_SUMMARY_FEMALE + $totalShift;

        foreach (self::MONTH_COLS as $mc) {
            $sheet->setCellValue("{$mc}{$rHdr}", "={$mc}\$10");
        }

        $sumRows = [
            $rAlloc  => 'L',
            $rActual => 'M',
            $rAttrib => 'N',
            $rTot    => 'O',
            $rMale   => 'E',
            $rFemale => 'F',
        ];

        foreach ($sumRows as $sRow => $targetCol) {
            foreach (self::MONTH_COLS as $mc) {
                $f = "=SUMIFS(\${$targetCol}\${$cS}:\${$targetCol}\${$cE},\$AE\${$cS}:\$AE\${$cE},\">=\"&{$mc}\$10,\$AE\${$cS}:\$AE\${$cE},\"<\"&EDATE({$mc}\$10,1),\$AH\${$cS}:\$AH\${$cE},1)"
                   . "+SUMIFS(\${$targetCol}\${$oS}:\${$targetCol}\${$oE},\$AE\${$oS}:\$AE\${$oE},\">=\"&{$mc}\$10,\$AE\${$oS}:\$AE\${$oE},\"<\"&EDATE({$mc}\$10,1),\$AH\${$oS}:\$AH\${$oE},1)"
                   . "+SUMIFS(\${$targetCol}\${$aS}:\${$targetCol}\${$aE},\$AE\${$aS}:\$AE\${$aE},\">=\"&{$mc}\$10,\$AE\${$aS}:\$AE\${$aE},\"<\"&EDATE({$mc}\$10,1),\$AH\${$aS}:\$AH\${$aE},1)";
                $sheet->setCellValue("{$mc}{$sRow}", $f);
            }
            $sheet->setCellValue("AC{$sRow}", "=SUM(Q{$sRow}:AB{$sRow})");
        }

        foreach (array_merge(self::MONTH_COLS, ['AC']) as $mc) {
            $sheet->setCellValue("{$mc}{$rBal}", "={$mc}{$rAlloc}-{$mc}{$rTot}");
        }

        foreach (self::MONTH_COLS as $mc) {
            $fAct = "=COUNTIFS(\$AE\${$cS}:\$AE\${$cE},\">=\"&{$mc}\$10,\$AE\${$cS}:\$AE\${$cE},\"<\"&EDATE({$mc}\$10,1),\$AH\${$cS}:\$AH\${$cE},1,\$C\${$cS}:\$C\${$cE},\"?*\")"
                  . "+COUNTIFS(\$AE\${$oS}:\$AE\${$oE},\">=\"&{$mc}\$10,\$AE\${$oS}:\$AE\${$oE},\"<\"&EDATE({$mc}\$10,1),\$AH\${$oS}:\$AH\${$oE},1,\$C\${$oS}:\$C\${$oE},\"?*\")"
                  . "+COUNTIFS(\$AE\${$aS}:\$AE\${$aE},\">=\"&{$mc}\$10,\$AE\${$aS}:\$AE\${$aE},\"<\"&EDATE({$mc}\$10,1),\$AH\${$aS}:\$AH\${$aE},1,\$C\${$aS}:\$C\${$aE},\"?*\")";
            $sheet->setCellValue("{$mc}{$rActCnt}", $fAct);
        }
        $sheet->setCellValue("AC{$rActCnt}", "=SUM(Q{$rActCnt}:AB{$rActCnt})");

        // Data Check rows
        $rChkExcl = self::BASE_CHECK_EXCLUDED + $totalShift;
        $rChkOut  = self::BASE_CHECK_OUTSIDE_FY + $totalShift;

        $sheet->setCellValue(
            "V{$rChkExcl}",
            "=SUMPRODUCT(ISNUMBER(\$O\${$cS}:\$O\${$cE})*(\$AH\${$cS}:\$AH\${$cE}=0))"
            . "+SUMPRODUCT(ISNUMBER(\$O\${$oS}:\$O\${$oE})*(\$AH\${$oS}:\$AH\${$oE}=0))"
            . "+SUMPRODUCT(ISNUMBER(\$O\${$aS}:\$O\${$aE})*(\$AH\${$aS}:\$AH\${$aE}=0))"
        );
        $sheet->setCellValue("V{$rChkOut}", "=O{$totalRow}-AC{$totalRow}");
    }

    private function applySectionConditionalFormatting(Worksheet $sheet, int $start, int $end): void
    {
        $cond = new Conditional();
        $cond->setConditionType(Conditional::CONDITION_EXPRESSION);
        $cond->setConditions(["AND(\$O{$start}<>\"\",\$AH{$start}=0)"]);
        $cond->getStyle()->getFont()->setItalic(true);
        $cond->getStyle()->getFont()->setColor(new Color('FF808080'));

        $sheet->setConditionalStyles("A{$start}:AC{$end}", [$cond]);
    }

    private function clearAllDataValidations(Worksheet $sheet): void
    {
        foreach (array_keys($sheet->getDataValidationCollection()) as $coordKey) {
            $sheet->setDataValidation($coordKey, null);
        }
    }
}

