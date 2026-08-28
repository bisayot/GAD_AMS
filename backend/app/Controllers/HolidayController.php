<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\HolidayModel;

class HolidayController extends ResourceController
{
    protected $modelName = 'App\Models\HolidayModel';
    protected $format    = 'json';

    // Get all holidays (optionally filter by year)
    public function index()
    {
        $year = $this->request->getGet('year');
        
        $model = new HolidayModel();
        
        if ($year) {
            $holidays = $model->where('YEAR(date)', $year)->orderBy('date', 'ASC')->findAll();
        } else {
            $holidays = $model->orderBy('date', 'ASC')->findAll();
        }

        return $this->respond([
            'status' => 'success',
            'data'   => $holidays
        ]);
    }

    // Create a custom holiday
    public function create()
    {
        $rules = [
            'date' => 'required|valid_date',
            'name' => 'required|max_length[255]',
            'type' => 'required|in_list[public,school,custom]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        
        // Ensure no duplicate date exists
        $model = new HolidayModel();
        $existing = $model->where('date', $data['date'])->first();
        if ($existing) {
            return $this->fail('A holiday already exists for this date.', 400);
        }

        $model->insert($data);
        return $this->respondCreated(['status' => 'success', 'message' => 'Holiday created']);
    }

    // Update an existing holiday
    public function update($id = null)
    {
        $model = new HolidayModel();
        if (!$model->find($id)) {
            return $this->failNotFound('Holiday not found');
        }

        $rules = [
            'date' => "required|valid_date|is_unique[holidays.date,id,{$id}]",
            'name' => 'required|max_length[255]',
            'type' => 'required|in_list[public,school,custom]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();
        $model->update($id, $data);

        return $this->respond(['status' => 'success', 'message' => 'Holiday updated']);
    }

    // Delete a holiday
    public function delete($id = null)
    {
        $model = new HolidayModel();
        if (!$model->find($id)) {
            return $this->failNotFound('Holiday not found');
        }

        $model->delete($id);
        return $this->respondDeleted(['status' => 'success', 'message' => 'Holiday deleted']);
    }

    // Sync holidays from Nager.Date API
    public function sync()
    {
        $year = $this->request->getJSON(true)['year'] ?? $this->request->getPost('year') ?? date('Y');

        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->request('GET', "https://date.nager.at/api/v3/PublicHolidays/{$year}/PH");
            $holidays = json_decode($response->getBody(), true);
            
            if (!$holidays || !is_array($holidays)) {
                return $this->fail('Failed to fetch holidays from external API', 500);
            }

            $model = new HolidayModel();
            $addedCount = 0;

            foreach ($holidays as $holiday) {
                // Check if already exists
                $existing = $model->where('date', $holiday['date'])->first();
                if (!$existing) {
                    $model->insert([
                        'date' => $holiday['date'],
                        'name' => $holiday['name'],
                        'type' => 'public',
                    ]);
                    $addedCount++;
                }
            }

            return $this->respond([
                'status' => 'success', 
                'message' => "Successfully synced {$addedCount} new public holidays for {$year}."
            ]);

        } catch (\Exception $e) {
            return $this->failServerError('Error communicating with holiday API: ' . $e->getMessage());
        }
    }
}
