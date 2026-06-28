<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class OfficeController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $db = \Config\Database::connect();
        $offices = $db->table('office_units')->orderBy('office_id', 'ASC')->get()->getResultArray();
        return $this->respond([
            'success' => true,
            'data' => $offices
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        if (empty($data['office_name'])) {
            return $this->failValidationErrors('Office name is required');
        }

        $cleanName = trim($data['office_name']);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);
        $cleanName = ucwords(strtolower($cleanName));

        $existing = $db->table('office_units')->where('office_name', $cleanName)->get()->getRowArray();
        if ($existing) {
            return $this->failResourceExists('Office/Unit already exists.');
        }

        $db->table('office_units')->insert(['office_name' => $cleanName]);
        $id = $db->insertID();

        return $this->respondCreated([
            'success' => true,
            'message' => 'Office added successfully',
            'data' => [
                'office_id' => $id,
                'office_name' => $cleanName
            ]
        ]);
    }

    public function update($id = null)
    {
        $db = \Config\Database::connect();
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        if (empty($data['office_name'])) {
            return $this->failValidationErrors('Office name is required');
        }

        $cleanName = trim($data['office_name']);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);
        $cleanName = ucwords(strtolower($cleanName));

        // Check if name exists for a DIFFERENT office
        $existing = $db->table('office_units')->where('office_name', $cleanName)->where('office_id !=', $id)->get()->getRowArray();
        if ($existing) {
            return $this->failResourceExists('Another Office/Unit with this name already exists.');
        }

        $db->table('office_units')->where('office_id', $id)->update(['office_name' => $cleanName]);

        return $this->respond([
            'success' => true,
            'message' => 'Office updated successfully',
            'data' => [
                'office_id' => $id,
                'office_name' => $cleanName
            ]
        ]);
    }

    public function delete($id = null)
    {
        $db = \Config\Database::connect();

        // Check if it's being used in users table
        $inUse = $db->table('users')->where('office_id', $id)->countAllResults();
        if ($inUse > 0) {
            return $this->fail('Cannot delete this office because there are users associated with it.');
        }

        $db->table('office_units')->where('office_id', $id)->delete();

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Office deleted successfully'
        ]);
    }
}
