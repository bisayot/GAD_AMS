<?php

namespace App\Controllers;

use App\Libraries\GpbTemplateWriter;
use App\Models\GpbModel;
use App\Models\SettingModel;

class GpbExportController extends BaseController
{
    /** GET /gpb/export/{fiscalYear} — builds the workbook from MySQL. */
    public function export(int $fiscalYear)
    {
        $model   = new GpbModel();
        $grouped = $model->getGroupedBySection($fiscalYear);

        // GpbModel rows already use 'budget' (single numeric column), so we
        // only need to rename it to 'budget_value' for the writer's contract.
        $otherLinesTotal = 0.0;
        foreach ($grouped as $section => $rows) {
            foreach ($rows as $i => $row) {
                $amount = (float) $row['budget'];
                $grouped[$section][$i]['budget_value'] = $amount;

                // Check budget_lines for source-specific totals if available
                $budgetLines = isset($row['budget_lines']) ? json_decode($row['budget_lines'], true) : [];
                if (is_array($budgetLines) && count($budgetLines) > 0) {
                    $sourceLines = [];
                    foreach ($budgetLines as $line) {
                        $lineAmount = (float) ($line['amount'] ?? 0);
                        $src = (string) ($line['source'] ?? '');
                        $spec = trim((string) ($line['sourceOther'] ?? ''));
                        
                        if (strtolower(trim($src)) === 'other') {
                            $otherLinesTotal += $lineAmount;
                            $sourceLines[] = $spec ? "Other ($spec)" : 'Other';
                        } else {
                            $sourceLines[] = $src;
                        }
                    }
                    // Update the source display for the writer if needed
                    $grouped[$section][$i]['source'] = implode("\n", $sourceLines);
                    $grouped[$section][$i]['budget_lines_raw'] = $budgetLines;
                } else {
                    // Fallback to the single source column
                    $source = strtolower(trim((string) ($row['source'] ?? '')));
                    if ($source === 'other') {
                        $otherLinesTotal += $amount;
                    }
                }
            }
        }

        // Fetch organization settings for the given fiscal year
        $settingModel = new SettingModel();
        $settings = $settingModel->getByFiscalYear($fiscalYear);
        $orgOther = (float) ($settings['otherSources'] ?? 0);

        $writer      = new GpbTemplateWriter();
        $spreadsheet = $writer->build($grouped, [
            'fiscal_year'      => 'FY ' . $fiscalYear,
            'org_name'         => $settings['name'] ?? '',
            'org_category'     => $settings['category'] ?? '',
            'org_hierarchy'    => $settings['hierarchy'] ?? '',
            'total_org_budget' => (float) ($settings['totalOrgBudget'] ?? 0),
            'other_sources'    => $otherLinesTotal + $orgOther,
            'org_other'        => $orgOther, // Pass this to help the writer adjust grandTotal
        ]);

        return $this->stream($spreadsheet, "gpb-{$fiscalYear}.xlsx");
    }

    private function stream(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, string $downloadName)
    {
        $tempPath = WRITEPATH . 'uploads/tmp_' . uniqid('gpb_', true) . '.xlsx';

        $xlsxWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $xlsxWriter->setPreCalculateFormulas(false);
        $xlsxWriter->save($tempPath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $xlsxWriter);

        register_shutdown_function(static function () use ($tempPath): void {
            if (is_file($tempPath)) {
                unlink($tempPath);
            }
        });

        return $this->response->download($tempPath, null)->setFileName($downloadName);
    }
}
