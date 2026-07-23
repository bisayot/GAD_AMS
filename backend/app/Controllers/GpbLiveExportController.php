<?php

namespace App\Controllers;

use App\Libraries\GpbTemplateWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Accepts the JSON `state` object straight from the browser-based GAD Plan
 * editor (window.storage-backed HTML tool) and returns a populated
 * gpb_template.xlsx — no MySQL involved. This is what wires the standalone
 * editor to the real template; GpbExportController (DB-driven) is unrelated
 * to it and keeps working independently.
 *
 * Expected POST body (application/json):
 * {
 *   "org": { "name","category","hierarchy","year","totalOrgBudget","otherSources" },
 *   "items": [
 *     { "section":"client|org|attributed", "mandate","cause","result","mfo",
 *       "activity","indicators","responsible",
 *       "budgetLines": [ { "label","amount","source" } ] }
 *   ]
 * }
 */
class GpbLiveExportController extends BaseController
{
    /**
     * Lock this down to your actual frontend origin before going to
     * production — e.g. 'https://your-app.example.com'. '*' is fine for
     * local development only.
     */
    private const ALLOWED_ORIGIN = '*';

    private const SECTION_MAP = [
        'client'     => 'client_focused',
        'org'        => 'organization_focused',
        'attributed' => 'attributed_program',
    ];

    public function export()
    {
        $this->applyCorsHeaders();

        if (strtoupper($this->request->getMethod()) === 'OPTIONS') {
            return $this->response->setStatusCode(204);
        }

        $payload = $this->request->getJSON(true);

        if (! is_array($payload) || empty($payload['items']) || ! is_array($payload['items'])) {
            return $this->response->setStatusCode(422)->setJSON([
                'error' => 'Malformed payload: expected an object with "org" and a non-empty "items" array.',
            ]);
        }

        $grouped = $this->normalizePayload($payload['items']);
        $meta    = $this->extractHeaderMeta($payload['org'] ?? []);

        $writer      = new GpbTemplateWriter();
        $spreadsheet = $writer->build($grouped, $meta);

        $filename = $this->slug($payload['org']['name'] ?? 'gad-plan') . '.xlsx';

        return $this->stream($spreadsheet, $filename);
    }

    private function applyCorsHeaders(): void
    {
        $this->response->setHeader('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
        $this->response->setHeader('Access-Control-Max-Age', '86400');
    }

    /**
     * Maps the editor's item shape (one 'section' key + a 'budgetLines'
     * array) onto GpbTemplateWriter's contract. Multiple budget lines are
     * stacked into one cell with newlines, mirroring how the original
     * paper/PDF template shows several PS/Supplies figures in a single
     * budget cell.
     */
    private function normalizePayload(array $items): array
    {
        $grouped = array_fill_keys(GpbTemplateWriter::SECTION_ORDER, []);

        foreach ($items as $item) {
            $section = self::SECTION_MAP[$item['section'] ?? ''] ?? null;
            if ($section === null) {
                continue; // unknown/legacy section key — skip rather than guess
            }

            $total        = 0.0;
            $budgetLines  = [];
            $sourceLines  = [];

            foreach (($item['budgetLines'] ?? []) as $line) {
                $amount = (float) ($line['amount'] ?? 0);
                $total += $amount;
                $label  = trim((string) ($line['label'] ?? '')) ?: 'Budget line';
                $budgetLines[] = $label . ' - ' . number_format($amount, 2);
                $sourceLines[] = (string) ($line['source'] ?? '');
            }

            $grouped[$section][] = [
                'mandate'        => $item['mandate'] ?? '',
                'cause'          => $item['cause'] ?? '',
                'objective'      => $item['result'] ?? '',
                'ppa'            => $item['mfo'] ?? '',
                'activity'       => $item['activity'] ?? '',
                'targets'        => $item['indicators'] ?? '',
                'budget_value'   => $total,
                'budget_display' => implode("\n", $budgetLines),
                'source'         => implode("\n", $sourceLines),
                'office'         => $item['responsible'] ?? '',
            ];
        }

        return $grouped;
    }

    private function extractHeaderMeta(array $org): array
    {
        return [
            'org_name'         => $org['name'] ?? '',
            'org_category'     => $org['category'] ?? '',
            'org_hierarchy'    => $org['hierarchy'] ?? '',
            'fiscal_year'      => $org['year'] ?? '',
            'total_org_budget' => (float) ($org['totalOrgBudget'] ?? 0),
            'other_sources'    => (float) ($org['otherSources'] ?? 0),
        ];
    }

    private function slug(string $text): string
    {
        $slug = strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '-', $text));
        return trim($slug, '-') ?: 'gad-plan';
    }

    private function stream(Spreadsheet $spreadsheet, string $downloadName)
    {
        $tempPath = WRITEPATH . 'uploads/tmp_' . uniqid('gpb_live_', true) . '.xlsx';

        $xlsxWriter = new Xlsx($spreadsheet);
        $xlsxWriter->setPreCalculateFormulas(false);
        $xlsxWriter->save($tempPath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $xlsxWriter);

        register_shutdown_function(static function () use ($tempPath): void {
            if (is_file($tempPath)) {
                unlink($tempPath);
            }
        });

        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $downloadName . '"');
        $this->response->setHeader('Content-Length', (string)filesize($tempPath));
        $this->response->setBody(file_get_contents($tempPath));

        return $this->response;
    }
}
