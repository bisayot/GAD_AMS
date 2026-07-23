<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GpbModel;
use CodeIgniter\API\ResponseTrait;

class GpbController extends BaseController
{
    use ResponseTrait;

    private const ALLOWED_ORIGIN = '*';

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->response->setHeader('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type');
    }

    public function options()
    {
        return $this->response->setStatusCode(204);
    }

    // Get all items for a fiscal year
    public function index($fiscalYear = null)
    {
        $model = new GpbModel();
        if ($fiscalYear) {
            $items = $model->where('fiscal_year', $fiscalYear)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
        } else {
            $items = $model->orderBy('fiscal_year', 'DESC')->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
        }
        return $this->respond($items);
    }

    // Get single item
    public function show($id = null)
    {
        $model = new GpbModel();
        $item = $model->find($id);
        if (!$item) {
            return $this->failNotFound('Item not found');
        }
        return $this->respond($item);
    }

    // Create item
    public function create()
    {
        $model = new GpbModel();
        $data = $this->request->getJSON(true);
        if ($model->save($data)) {
            return $this->respondCreated(['id' => $model->getInsertID()]);
        }
        return $this->failValidationErrors($model->errors());
    }

    // Update item
    public function update($id = null)
    {
        $model = new GpbModel();
        $item = $model->find($id);
        if (!$item) {
            return $this->failNotFound('Item not found');
        }
        $data = $this->request->getJSON(true);
        $data['id'] = $id;
        if ($model->save($data)) {
            return $this->respond(['success' => true]);
        }
        return $this->failValidationErrors($model->errors());
    }

    // Delete item
    public function delete($id = null)
    {
        $model = new GpbModel();
        if ($model->delete($id)) {
            return $this->respondDeleted(['success' => true]);
        }
    }

    // Bulk import items
    public function import()
    {
        $model = new GpbModel();
        $data = $this->request->getJSON(true);
        
        if (!is_array($data) || empty($data)) {
            return $this->failValidationErrors('Invalid or empty data for import.');
        }

        try {
            if ($model->insertBatch($data)) {
                return $this->respondCreated(['success' => true, 'count' => count($data)]);
            }
        } catch (\Exception $e) {
            return $this->failServerError('Database error during import: ' . $e->getMessage());
        }

        return $this->failValidationErrors($model->errors());
    }
}
