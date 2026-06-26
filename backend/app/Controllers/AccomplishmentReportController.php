<?php

namespace App\Controllers;

use App\Models\AccomplishmentReportModel;
use App\Libraries\FileStorage;

class AccomplishmentReportController extends BaseController
{
    public function submitReport()
    {
        $accomplishmentReportModel = new AccomplishmentReportModel();

        // Validation rules aligned with frontend FormData field names (underscores)
        $rules = [
            "activity_title" => "required",
            "control_number" => "required",
            "start_date"     => "required",
            "end_date"       => "required",
            "start_time"     => "required",
            "end_time"       => "required",
            "venue"          => "required",
            "attendees"      => "required|integer",
            "male"           => "required|integer",
            "female"         => "required|integer",
            "rating"         => "required|numeric",
            "user_id"        => "required",
            "attachment"     => "uploaded[attachment]|max_size[attachment,10240]|ext_in[attachment,pdf,doc,docx]",
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                "success" => false,
                "errors"  => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        try {
            // Save uploaded PDF to writable/uploads/drafts/
            $file = $this->request->getFile('attachment');
            $fileName = FileStorage::saveToDrafts($file);

            $db = \Config\Database::connect();
            $controlRecord = $db->table('control_number')
                                ->where('control_number', $this->request->getPost("control_number"))
                                ->get()->getRowArray();
            $actDesignId = $controlRecord ? $controlRecord['act_design_id'] : null;

            $data = [
                "activity_title" => $this->request->getPost("activity_title"),
                "control_number" => $this->request->getPost("control_number"),
                "act_design_id"  => $actDesignId,
                "start_date"     => $this->request->getPost("start_date"),
                "end_date"       => $this->request->getPost("end_date"),
                "start_time"     => $this->request->getPost("start_time"),
                "end_time"       => $this->request->getPost("end_time"),
                "venue"          => $this->request->getPost("venue"),
                "attendees"      => $this->request->getPost("attendees"),
                "male"           => $this->request->getPost("male"),
                "female"         => $this->request->getPost("female"),
                "rating"         => $this->request->getPost("rating"),
                "user_id"        => $this->request->getPost("user_id"),
                "attachment"     => $fileName,
                "status"         => "Pending",
            ];

            if (empty($data['user_id'])) {
                throw new \Exception("User ID is missing. Please log in again.");
            }


            if ($accomplishmentReportModel->insert($data)) {
                $reportId = $accomplishmentReportModel->getInsertID();

                // Save budget items
                $budgetItemsJson = $this->request->getPost('budget_items');
                if (!empty($budgetItemsJson)) {
                    $budgetData = json_decode($budgetItemsJson, true);
                    if (is_array($budgetData)) {
                        $budgetData['accomplishment_report_id'] = $reportId;
                        $budgetModel = new \App\Models\AccomplishmentBudgetItemsModel();
                        $budgetModel->insert($budgetData);
                    }
                }

                // Save evaluation results
                $evalItemsJson = $this->request->getPost('evaluation_results');
                if (!empty($evalItemsJson)) {
                    $evalData = json_decode($evalItemsJson, true);
                    if (is_array($evalData)) {
                        $evalData['accomplishment_report_id'] = $reportId;
                        $evalModel = new \App\Models\AccomplishmentEvaluationResultsModel();
                        $evalModel->insert($evalData);
                    }
                }

                \App\Models\ActivityLogModel::log($data['user_id'], 'Submit Document', 'submitted Accomplishment Report: ' . $data['activity_title']);

                return $this->response->setJSON([
                    "success" => true,
                    "message" => "Data saved successfully"
                ]);
            }


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
        $db = \Config\Database::connect();

        $active = $db->table('accomplishment_report as ar')
            ->select('ar.id, ar.status, cn.control_number as control, ar.activity_title as title, DATE(ar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, ad.form_type as formLabel')
            ->join('users', 'users.id = ar.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number as cn', 'cn.control_number = ar.control_number', 'left')
            ->join('archived_activity_designs as ad', 'ad.original_act_design_id = cn.act_design_id', 'left')
            ->where('ar.status !=', 'Verified')
            ->where('ar.deleted_at', null)
            ->get()->getResultArray();

        $archived = $db->table('archived_accomplishment_reports as aar')
            ->select('aar.original_report_id as id, aar.status, cn.control_number as control, aar.activity_title as title, DATE(aar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, ad.form_type as formLabel')
            ->join('users', 'users.id = aar.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number as cn', 'cn.control_number = aar.control_number', 'left')
            ->join('archived_activity_designs as ad', 'ad.original_act_design_id = cn.act_design_id', 'left')
            ->where('aar.status !=', 'Verified')
            ->get()->getResultArray();

        $reports = array_merge($active, $archived);
        usort($reports, function($a, $b) {
            $dateCompare = strcmp($a['date'] ?? '', $b['date'] ?? '');
            return $dateCompare !== 0 ? $dateCompare : ($a['id'] <=> $b['id']);
        });

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
            ->select('accomplishment_report.*, control_number.control_number as control, DATE(accomplishment_report.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, activity_design.form_type as formLabel')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number', 'control_number.control_number = accomplishment_report.control_number', 'left')
            ->join('activity_design', 'activity_design.act_design_id = control_number.act_design_id', 'left')
            ->where('accomplishment_report.id', $id)
            ->first();

        if (!$report) {
            $db = \Config\Database::connect();
            $report = $db->table('archived_accomplishment_reports as aar')
                ->select('aar.*, aar.original_report_id as id, aar.activity_title as title, DATE(aar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, aar.control_number as control')
                ->join('users', 'users.id = aar.user_id', 'left')
                ->join('office_units', 'office_units.office_id = users.office_id', 'left')
                ->where('aar.original_report_id', $id)
                ->get()->getRowArray();

            if (!$report) {
                return $this->response->setJSON(['success' => false, 'message' => 'Report not found'])->setStatusCode(404);
            }
            $report['is_archived'] = true;
        } else {
            $report['is_archived'] = false;
        }


        if ($report) {
            $budgetModel = new \App\Models\AccomplishmentBudgetItemsModel();
            $report['budget_items'] = $budgetModel->where('accomplishment_report_id', $id)->findAll();
            


            $evalModel = new \App\Models\AccomplishmentEvaluationResultsModel();
            $report['evaluation_results'] = $evalModel->where('accomplishment_report_id', $id)->findAll();

            $controlNumber = $report['control'] ?? $report['control_number'] ?? null;
            if ($controlNumber) {
                $db = \Config\Database::connect();
                $ad = $db->table('archived_activity_designs as aad')
                    ->select('aad.*, venues.venue_name')
                    ->join('control_number as cn', 'cn.act_design_id = aad.original_act_design_id', 'left')
                    ->join('venues', 'venues.venue_id = aad.venue_id', 'left')
                    ->where('cn.control_number', $controlNumber)
                    ->get()->getRowArray();
                
                if ($ad) {
                    $adBudgetModel = new \App\Models\ActivityBudgetItemsModel();
                    $ad['budget_items'] = $adBudgetModel->where('act_design_id', $ad['original_act_design_id'])->findAll();
                    $report['activity_design'] = $ad;
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'data' => $report]);

    }

    public function getUserReports($userId = null)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        $active = $db->table('accomplishment_report as ar')
            ->select('ar.id, ar.status, cn.control_number as control, ar.activity_title as title, DATE(ar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, ad.form_type as formLabel')
            ->join('users', 'users.id = ar.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number as cn', 'cn.control_number = ar.control_number', 'left')
            ->join('archived_activity_designs as ad', 'ad.original_act_design_id = cn.act_design_id', 'left')
            ->where('ar.user_id', $userId)
            ->where('ar.status !=', 'Verified')
            ->where('ar.deleted_at', null)
            ->get()->getResultArray();

        $archived = $db->table('archived_accomplishment_reports as aar')
            ->select('aar.original_report_id as id, aar.status, cn.control_number as control, aar.activity_title as title, DATE(aar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, ad.form_type as formLabel')
            ->join('users', 'users.id = aar.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number as cn', 'cn.control_number = aar.control_number', 'left')
            ->join('archived_activity_designs as ad', 'ad.original_act_design_id = cn.act_design_id', 'left')
            ->where('aar.user_id', $userId)
            ->where('aar.status !=', 'Verified')
            ->get()->getResultArray();

        $reports = array_merge($active, $archived);
        usort($reports, function($a, $b) {
            $dateCompare = strcmp($a['date'] ?? '', $b['date'] ?? '');
            return $dateCompare !== 0 ? $dateCompare : ($a['id'] <=> $b['id']);
        });

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports
        ]);
    }

    public function getArchivedReports()
    {
        $accomplishmentReportModel = new AccomplishmentReportModel();

        $reports = $accomplishmentReportModel
            ->select('accomplishment_report.*, control_number.control_number as control, accomplishment_report.activity_title as title, DATE(accomplishment_report.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, activity_design.form_type as formLabel')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
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

    public function updateReport($id)
    {
        $model = new AccomplishmentReportModel();

        $report = $model->find($id);
        if (!$report) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Accomplishment report record #$id not found."
            ])->setStatusCode(404);
        }

        // Collect only the fields sent in the request
        $data = [
            'activity_title' => $this->request->getPost('activity_title'),
            'start_date'     => $this->request->getPost('start_date'),
            'end_date'       => $this->request->getPost('end_date'),
            'start_time'     => $this->request->getPost('start_time'),
            'end_time'       => $this->request->getPost('end_time'),
            'venue'          => $this->request->getPost('venue'),
            'attendees'      => $this->request->getPost('attendees'),
            'male'           => $this->request->getPost('male'),
            'female'         => $this->request->getPost('female'),
            'rating'         => $this->request->getPost('rating'),
            'status'         => $this->request->getPost('status') ?? 'Pending',
        ];

        // Remove null/empty values so we only update provided fields
        $updateData = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        // Handle new file upload (if any) — saves to drafts, removes old draft
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!empty($report['attachment'])) {
                FileStorage::deleteFromDrafts($report['attachment']);
            }
            $newName = FileStorage::saveToDrafts($file);
            $updateData['attachment'] = $newName;
        }

        try {
            if ($model->update($id, $updateData)) {
                
                // Update or Insert budget items
                $budgetItemsJson = $this->request->getPost('budget_items');
                if (!empty($budgetItemsJson)) {
                    $budgetData = json_decode($budgetItemsJson, true);
                    if (is_array($budgetData)) {
                        $budgetModel = new \App\Models\AccomplishmentBudgetItemsModel();
                        $existingBudget = $budgetModel->where('accomplishment_report_id', $id)->first();
                        if ($existingBudget) {
                            $budgetModel->update($existingBudget['item_id'], $budgetData);
                        } else {
                            $budgetData['accomplishment_report_id'] = $id;
                            $budgetModel->insert($budgetData);
                        }
                    }
                }

                // Update or Insert evaluation results
                $evalItemsJson = $this->request->getPost('evaluation_results');
                if (!empty($evalItemsJson)) {
                    $evalData = json_decode($evalItemsJson, true);
                    if (is_array($evalData)) {
                        $evalModel = new \App\Models\AccomplishmentEvaluationResultsModel();
                        $existingEval = $evalModel->where('accomplishment_report_id', $id)->first();
                        if ($existingEval) {
                            $evalModel->update($existingEval['evaluation_id'], $evalData);
                        } else {
                            $evalData['accomplishment_report_id'] = $id;
                            $evalModel->insert($evalData);
                        }
                    }
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Accomplishment Report updated and resubmitted successfully.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database update failed.',
                    'errors'  => $model->errors()
                ])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Approve (Verify) an Accomplishment Report:
     *  1. Updates status to 'Verified'
     *  2. Inserts into archived_accomplishment_reports table
     *  3. Deletes from active accomplishment_report table
     *  4. Moves the PDF from drafts → archived
     */
    public function approveReport($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $body    = $this->request->getJSON(true) ?? $this->request->getPost();
        $remarks = $body['remarks'] ?? '';

        $db = \Config\Database::connect();
        $db->transStart();

        $item = $db->table('accomplishment_report')->where('id', $id)->get()->getRowArray();
        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report not found'])->setStatusCode(404);
        }

        // Insert into archived_accomplishment_reports
        $archiveData = [
            'original_report_id' => $item['id'],
            'control_number'     => $item['control_number'],
            'act_design_id'      => $item['act_design_id'] ?? null,
            'activity_title'     => $item['activity_title'],
            'start_date'         => $item['start_date'],
            'end_date'           => $item['end_date'],
            'start_time'         => $item['start_time'],
            'end_time'           => $item['end_time'],
            'venue'              => $item['venue'],
            'attendees'          => $item['attendees'],
            'male'               => $item['male'],
            'female'             => $item['female'],
            'rating'             => $item['rating'],
            'attachment'         => $item['attachment'],
            'user_id'            => $item['user_id'],
            'status'             => 'Verified',
            'remarks'            => $remarks,
        ];
        $db->table('archived_accomplishment_reports')->insert($archiveData);

        // Delete from active table
        $db->table('accomplishment_report')->where('id', $id)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to verify and archive report'])->setStatusCode(500);
        }

        // Move PDF from drafts → archived (outside transaction)
        FileStorage::moveToArchived($item['attachment']);

        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $item['user_id'];
        \App\Models\ActivityLogModel::log($actionUserId, 'Approve Document', 'verified Accomplishment Report: ' . $item['activity_title']);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Accomplishment Report verified and archived successfully.'
        ]);
    }

    public function revisionReport($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $body = $this->request->getJSON(true) ?? $this->request->getPost();
        $remarks = $body['remarks'] ?? '';
        $deadline = $body['deadline'] ?? null;

        $db = \Config\Database::connect();
        
        $updateData = [
            'status' => 'Revision Required'
        ];
        
        try {
            $updateData['remarks'] = $remarks;
            if ($deadline) {
                // If accomplishment_report had a deadline column, update it here
            }
            $db->table('accomplishment_report')->where('id', $id)->update($updateData);
        } catch (\Exception $e) {
            $db->table('accomplishment_report')->where('id', $id)->update(['status' => 'Revision Required']);
        }

        $item = $db->table('accomplishment_report')->where('id', $id)->get()->getRowArray();
        if ($item) {
            $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $item['user_id'];
            \App\Models\ActivityLogModel::log($actionUserId, 'Update Status', 'requested revision for Accomplishment Report: ' . $item['activity_title']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Sent for revision successfully'
        ]);
    }

    public function trash($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $model = new AccomplishmentReportModel();
        
        // Find if it exists first
        $report = $model->find($id);
        if (!$report) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accomplishment report not found'])->setStatusCode(404);
        }

        if ($model->delete($id)) {
            $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $report['user_id'];
            \App\Models\ActivityLogModel::log($actionUserId, 'Trash Document', 'moved to trash Accomplishment Report: ' . $report['activity_title']);
            return $this->response->setJSON(['success' => true, 'message' => 'Accomplishment report moved to trash successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to move accomplishment report to trash'])->setStatusCode(500);
    }
}