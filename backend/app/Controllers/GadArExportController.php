<?php

namespace App\Controllers;

use App\Libraries\GadArTemplateWriter;
use App\Libraries\GadUtilizationService;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GadArExportController extends BaseController
{
    use ResponseTrait;

    /**
     * GET /gad-ar/fiscal-years
     * Returns available fiscal years (newest first) for the Distribution page dropdown.
     */
    public function fiscalYears()
    {
        $service = new GadUtilizationService();
        return $this->respond([
            'success' => true,
            'years'   => $service->getAvailableFiscalYears(),
        ]);
    }

    /**
     * GET /gad-ar/export?fiscal_year=YYYY
     * Generates and streams GAD_Budget_Utilization_FY{year}.xlsx.
     */
    public function export()
    {
        $fiscalYear = (int) ($this->request->getGet('fiscal_year') ?: date('Y'));
        if ($fiscalYear < 2000 || $fiscalYear > 2100) {
            return $this->failValidationErrors('A valid fiscal_year between 2000 and 2100 is required.');
        }

        try {
            $service     = new GadUtilizationService();
            $grouped     = $service->buildGroupedRows($fiscalYear);
            $writer      = new GadArTemplateWriter();
            $spreadsheet = $writer->build($grouped, $fiscalYear);

            return $this->stream($spreadsheet, "GAD_Budget_Utilization_FY{$fiscalYear}.xlsx");
        } catch (\Throwable $e) {
            log_message('error', 'GAD AR Excel export failed: {message} in {file}:{line}', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return $this->failServerError('Failed to generate Excel export: ' . $e->getMessage());
        }
    }

    private function stream(Spreadsheet $spreadsheet, string $downloadName)
    {
        $uploadDir = WRITEPATH . 'uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $tempPath = $uploadDir . '/tmp_' . uniqid('gad_ar_', true) . '.xlsx';

        $xlsxWriter = new Xlsx($spreadsheet);
        $xlsxWriter->setPreCalculateFormulas(false);
        $xlsxWriter->save($tempPath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $xlsxWriter);

        register_shutdown_function(static function () use ($tempPath): void {
            if (is_file($tempPath)) {
                @unlink($tempPath);
            }
        });

        return $this->response
            ->setHeader('Access-Control-Expose-Headers', 'Content-Disposition')
            ->download($tempPath, null)
            ->setFileName($downloadName);
    }
}

