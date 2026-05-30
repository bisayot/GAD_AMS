<?php

namespace App\Controllers;

use App\Models\AccomplishmentReportModel;

class AccomplishmentReportController extends BaseController
{
    public function submitReport()
    {
        $accomplishmentReportModel = new AccomplishmentReportModel();

        // FIX 1: Ensure upload parameter name aligns with frontend append statement ('attachment')
        $rules = [
            "activity-title"      => "required",
            "control-number"      => "required",
            "start-date"          => "required",
            "end-date"            => "required",
            "start-time"          => "required",
            "end-time"            => "required",
            "venue"               => "required",
            "attendees"           => "required|integer",
            "male"                => "required|integer",
            "female"              => "required|integer",
            "rating"              => "required|integer",
            "user_id"             => "required",
            "attachment"         => "uploaded[attachment]|max_size[attachment,10240]|ext_in[attachment,pdf]",
        ];

        if (!$this->validate($rules)) { // Removed $messages as it's not defined
            // FIX 2: Return errors as a JSON object, not a view
            return $this->response->setJSON([
                "success" => false,
                "errors"  => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        try {
            // FIX 3: Process and store physical upload file appropriately
            $file = $this->request->getFile('attachment');
            $fileName = '';

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $uploadPath = FCPATH . 'uploads';

                // Automatically create the directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $file->move($uploadPath, $fileName);
            }

            $data = [
                "activity_title"      => $this->request->getPost("activity-title"),
                "control_number"      => $this->request->getPost("control-number"),
                "start_date"          => $this->request->getPost("start-date"),
                "end_date"            => $this->request->getPost("end-date"),
                "start_time"          => $this->request->getPost("start-time"),
                "end_time"            => $this->request->getPost("end-time"),
                "venue"               => $this->request->getPost("venue"),
                "attendees"           => $this->request->getPost("attendees"),
                "male"                => $this->request->getPost("male"),
                "female"              => $this->request->getPost("female"),
                "rating"              => $this->request->getPost("rating"),
                "user_id"             => $this->request->getPost("user_id"),
                "attachment"          => $fileName,
                "status"              => "Pending",
            ];

            if (empty($data['user_id'])) {
                throw new \Exception("User ID is missing. Please log in again.");
            }

            if ($accomplishmentReportModel->insert($data)) {
                return $this->response->setJSON([
                    "success" => true,
                    "message" => "Data saved successfully"
                ]);
            }

            // If insertion fails (e.g. model validation), return specific errors
            return $this->response->setJSON([
                "success" => false,
                "message" => "Failed to save data into database.",
                "errors"  => $accomplishmentReportModel->errors()
            ])->setStatusCode(500);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                "success" => false,
                "message" => "Server Error: " . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function index()
    {
        $accomplishmentReportModel = new AccomplishmentReportModel();
        
        // Fetch reports with joined data for the list view
        $reports = $accomplishmentReportModel
            ->select('accomplishment_report.*, control_number.control_number as control, accomplishment_report.activity_title as title, DATE_FORMAT(accomplishment_report.created_at, "%Y-%m-%d") as date, users.username as office, activity_design.form_type as formLabel')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('control_number', 'control_number.control_number = accomplishment_report.control_number', 'left')
            ->join('activity_design', 'activity_design.act_design_id = control_number.act_design_id', 'left')
            ->orderBy('accomplishment_report.id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports
        ]);
    }

    public function show($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $accomplishmentReportModel = new AccomplishmentReportModel();
        $report = $accomplishmentReportModel
            ->select('accomplishment_report.*, control_number.control_number as control, DATE_FORMAT(accomplishment_report.created_at, "%Y-%m-%d") as date, users.username as office, activity_design.form_type as formLabel')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('control_number', 'control_number.control_number = accomplishment_report.control_number', 'left')
            ->join('activity_design', 'activity_design.act_design_id = control_number.act_design_id', 'left')
            ->where('accomplishment_report.id', $id)
            ->first();

        if (!$report) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report not found'])->setStatusCode(404);
        }

        return $this->response->setJSON(['success' => true, 'data' => $report]);
    }


    public function getUserReports($userId = null)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        $accomplishmentReportModel = new AccomplishmentReportModel();
        $reports = $accomplishmentReportModel
                                       ->select('accomplishment_report.*, control_number.control_number as control, accomplishment_report.activity_title as title, DATE_FORMAT(accomplishment_report.created_at, "%Y-%m-%d") as date, users.username as office, activity_design.form_type as formLabel')
                                       ->join('users', 'users.id = accomplishment_report.user_id', 'left')
                                       ->join('control_number', 'control_number.control_number = accomplishment_report.control_number', 'left')
                                       ->join('activity_design', 'activity_design.act_design_id = control_number.act_design_id', 'left')
                                       ->where('accomplishment_report.user_id', $userId)
                                       ->orderBy('accomplishment_report.id', 'DESC')
                                       ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports
        ]);
    }

    public function getArchivedReports()
    {
        $accomplishmentReportModel = new AccomplishmentReportModel();

        // Fetch reports that are 'Verified' or 'Cancelled'
        $reports = $accomplishmentReportModel
            ->select('accomplishment_report.*, control_number.control_number as control, accomplishment_report.activity_title as title, DATE_FORMAT(accomplishment_report.created_at, "%Y-%m-%d") as date, users.username as office, activity_design.form_type as formLabel')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('control_number', 'control_number.control_number = accomplishment_report.control_number', 'left')
            ->join('activity_design', 'activity_design.act_design_id = control_number.act_design_id', 'left')
            ->whereIn('accomplishment_report.status', ['Verified', 'Cancelled'])
            ->orderBy('accomplishment_report.id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports
        ]);
    }
}