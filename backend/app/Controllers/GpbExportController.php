<?php

namespace App\Controllers;

use App\Libraries\GpbTemplateWriter;
use App\Models\GpbModel;

class GpbExportController extends BaseController
{
    /** GET /gpb/export/{fiscalYear} — builds the workbook from MySQL. */
    public function export(int $fiscalYear)
    {
        $model   = new GpbModel();
        $grouped = $model->getGroupedBySection($fiscalYear);

        // GpbModel rows already use 'budget' (single numeric column), so we
        // only need to rename it to 'budget_value' for the writer's contract.
        foreach ($grouped as $section => $rows) {
            foreach ($rows as $i => $row) {
                $grouped[$section][$i]['budget_value'] = (float) $row['budget'];
            }
        }

        $writer      = new GpbTemplateWriter();
        $spreadsheet = $writer->build($grouped, [
            'fiscal_year' => 'FY ' . $fiscalYear,
            // Wire these to your organizations/settings table when you have one:
            // 'org_name' => ..., 'org_category' => ..., 'org_hierarchy' => ...,
            // 'total_org_budget' => ..., 'other_sources' => ...,
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
