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

        $authUserId = \App\Libraries\JwtHelper::getAuthUserId($this->request);
        $userId = $authUserId > 0 ? $authUserId : null;
        $fiscalYear = (int)$data->fiscal_year;
        $db = \Config\Database::connect();
        $version = $db->tableExists('budget_versions')
            ? (new \App\Libraries\BudgetVersionService($db))->active($fiscalYear)
            : null;

        $model = new ArchivedAnnualReport();
        $model->insert([
            'fiscal_year' => $fiscalYear,
            'plan_version_id' => $version['id'] ?? null,
            'version_number' => $version['version_number'] ?? null,
            'html_content' => $data->html_content,
            'created_by' => $userId
        ]);

        return $this->respondCreated(['success' => true, 'message' => 'Report published successfully']);
    }

    public function index()
    {
        $model = new ArchivedAnnualReport();
        // Fetch all without html_content to keep response small
        $reports = $model->select('id, fiscal_year, plan_version_id, version_number, created_by, created_at')
                         ->orderBy('created_at', 'DESC')
                         ->findAll();
                         
        return $this->respond(['success' => true, 'data' => $reports]);
    }

    public function show($id)
    {
        $model = new ArchivedAnnualReport();
        $report = $model->find($id);

        if (!$report) {
            return $this->failNotFound('Published report not found');
        }

        return $this->respond(['success' => true, 'data' => $report]);
    }

    public function delete($id)
    {
        $model = new ArchivedAnnualReport();
        $report = $model->find($id);

        if (!$report) {
            return $this->failNotFound('Published report not found');
        }

        if (!$model->delete($id)) {
            return $this->failServerError('Published report could not be deleted');
        }

        return $this->respondDeleted(['success' => true, 'message' => 'Published report deleted successfully']);
    }
}
