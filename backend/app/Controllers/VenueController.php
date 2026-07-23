<?php

namespace App\Controllers;

use App\Models\VenueModel;
use CodeIgniter\API\ResponseTrait;

class VenueController extends BaseController
{
    use ResponseTrait;

    private const ALLOWED_ORIGIN = '*';

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->response->setHeader('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN);
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    public function index()
    {
        $model = new VenueModel();
        $venues = $model->orderBy('venue_name', 'ASC')->findAll();
        
        foreach ($venues as &$venue) {
            $venue['is_inside_bsu'] = (bool)$venue['is_inside_bsu'];
        }
        
        return $this->respond($venues);
    }

    public function create()
    {
        $model = new VenueModel();
        $data = $this->request->getJSON(true);
        
        if (empty($data['venue_name'])) {
            return $this->failValidationErrors('Venue name is required');
        }

        $isInside = isset($data['is_inside_bsu']) ? (bool)$data['is_inside_bsu'] : true;
        
        $insertData = [
            'venue_name' => $data['venue_name'],
            'is_inside_bsu' => $isInside ? 1 : 0
        ];

        if ($model->insert($insertData)) {
            $insertData['venue_id'] = $model->getInsertID();
            $insertData['is_inside_bsu'] = $isInside;
            return $this->respondCreated($insertData);
        }
        
        return $this->failValidationErrors($model->errors());
    }

    public function update($id = null)
    {
        $model = new VenueModel();
        $venue = $model->find($id);
        
        if (!$venue) {
            return $this->failNotFound('Venue not found');
        }

        $data = $this->request->getJSON(true);
        
        $updateData = [];
        if (isset($data['venue_name'])) {
            if (empty(trim($data['venue_name']))) {
                return $this->failValidationErrors('Venue name cannot be empty');
            }
            $updateData['venue_name'] = $data['venue_name'];
        }
        
        if (isset($data['is_inside_bsu'])) {
            $updateData['is_inside_bsu'] = (bool)$data['is_inside_bsu'] ? 1 : 0;
        }

        if (!empty($updateData)) {
            if ($model->update($id, $updateData)) {
                $updated = $model->find($id);
                $updated['is_inside_bsu'] = (bool)$updated['is_inside_bsu'];
                return $this->respond($updated);
            }
            return $this->failValidationErrors($model->errors());
        }
        
        return $this->respond($venue);
    }

    public function delete($id = null)
    {
        $model = new VenueModel();
        
        if (!$model->find($id)) {
            return $this->failNotFound('Venue not found');
        }
        
        if ($model->delete($id)) {
            return $this->respondDeleted(['success' => true]);
        }
        
        return $this->failServerError('Failed to delete venue');
    }
}
