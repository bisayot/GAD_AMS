<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ArchivedAnnualReport;

class AnnualReportArchiveController extends BaseController
{
    use ResponseTrait;

    public function archive()
    {
        $data = $this->request->getJSON();
        if (!$data || !isset($data->fiscal_year) || !isset($data->html_content)) {
            return $this->fail('Fiscal year and HTML content are required');
        }

        $userId = $this->request->getHeaderLine('X-User-Id') ?: null;

        $model = new ArchivedAnnualReport();
        $model->insert([
            'fiscal_year' => $data->fiscal_year,
            'html_content' => $data->html_content,
            'created_by' => $userId
        ]);

        return $this->respondCreated(['success' => true, 'message' => 'Report archived successfully']);
    }

    public function index()
    {
        $model = new ArchivedAnnualReport();
        // Fetch all without html_content to keep response small
        $reports = $model->select('id, fiscal_year, created_by, created_at')
                         ->orderBy('created_at', 'DESC')
                         ->findAll();
                         
        return $this->respond(['success' => true, 'data' => $reports]);
    }

    public function show($id)
    {
        $model = new ArchivedAnnualReport();
        $report = $model->find($id);

        if (!$report) {
            return $this->failNotFound('Archived report not found');
        }

        return $this->respond(['success' => true, 'data' => $report]);
    }
}
