<?php

namespace App\Controllers;

use App\Models\AccomplishmentReportModel;
use App\Models\ApprovedControlModel;
use App\Libraries\FileStorage;

class AccomplishmentReportController extends BaseController
{
    public function submitReport()
    {
        $reportModel = new AccomplishmentReportModel();

        $rules = [
            'control_number'  => 'required',
            'act_design_id'   => 'required|numeric',
            'activity_title'  => 'required',
            'start_date'      => 'required',
            'end_date'        => 'required',
            'start_time'      => 'required',
            'end_time'        => 'required',
            'venue'           => 'required',
            'attendees'       => 'required|numeric',
            'male'            => 'required|numeric',
            'female'          => 'required|numeric',
            'rating'          => 'required',
            'user_id'         => 'required|numeric',
            'budget_items'    => 'required',
            'evaluation_items'=> 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $files = $this->request->getFiles();
            $fileNames = [];
            if (!empty($files['attachment'])) {
                foreach ($files['attachment'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileName = FileStorage::saveToDrafts($file);
                        $fileNames[] = $fileName;
                    }
                }
            }

            $reportData = [
                'control_number'  => $this->request->getPost('control_number'),
                'act_design_id'   => $this->request->getPost('act_design_id'),
                'activity_title'  => $this->request->getPost('activity_title'),
                'start_date'      => $this->request->getPost('start_date'),
                'end_date'        => $this->request->getPost('end_date'),
                'start_time'      => $this->request->getPost('start_time'),
                'end_time'        => $this->request->getPost('end_time'),
                'venue'           => $this->request->getPost('venue'),
                'attendees'       => $this->request->getPost('attendees'),
                'male'            => $this->request->getPost('male'),
                'female'          => $this->request->getPost('female'),
                'rating'          => $this->request->getPost('rating'),
                'attachment'      => json_encode($fileNames),
                'user_id'         => $this->request->getPost('user_id'),
                'status'          => $this->request->getPost('status') ?: 'Pending'
            ];

            // Validate that actual spending does not exceed proposed budget of the activity design
            $actDesignId = $reportData['act_design_id'];
            if (!empty($actDesignId)) {
                $design = $db->table('archived_activity_designs')->where('original_act_design_id', $actDesignId)->get()->getRowArray();
                if (!$design) {
                    $design = $db->table('activity_design')->where('act_design_id', $actDesignId)->get()->getRowArray();
                }

                if ($design) {
                    $proposedBudget = (float) $design['proposed_budget'];
                    
                    // Sum the actual budget items being submitted
                    $budgetItems = json_decode($this->request->getPost('budget_items'), true);
                    $actualTotal = 0;
                    if (!empty($budgetItems)) {
                        foreach ($budgetItems as $item) {
                            $actualTotal += (float) ($item['amount'] ?? $item['total'] ?? 0);
                        }
                    }

                    if ($actualTotal > $proposedBudget) {
                        throw new \Exception("Actual spending (PHP " . number_format($actualTotal, 2) . ") exceeds the approved proposed budget limit (PHP " . number_format($proposedBudget, 2) . "). Please file an Activity Design Revision first to adjust the budget.");
                    }
                }
            }

            $reportId = $reportModel->insert($reportData);

            if (!$reportId) {
                throw new \Exception("Failed to insert main report.");
            }

            // 3. Handle Actual Budget Items
            $budgetItems = json_decode($this->request->getPost('budget_items'), true);
            if (!empty($budgetItems)) {
                foreach ($budgetItems as $item) {
                    $db->table('accomplishment_budget_items')->insert([
                        'accomplishment_report_id' => $reportId,
                        'category'                 => $item['category'] ?? 'Miscellaneous',
                        'item_name'                => $item['name'] ?? 'Other',
                        'sub_item'                 => $item['sub_item'] ?? null,
                        'pax'                      => isset($item['pax']) && $item['pax'] !== '' ? (int)$item['pax'] : null,
                        'amount'                   => isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : 0.00
                    ]);
                }
            }

            // 4. Handle Evaluation Results
            $evaluationItems = json_decode($this->request->getPost('evaluation_items'), true);
            if (!empty($evaluationItems)) {
                $evalData = ['accomplishment_report_id' => $reportId];
                $evalMapping = [
                    'Time Management'                   => 'time_management',
                    'Orderliness and Program Flow'      => 'orderliness_and_program_flow',
                    'Appropriateness of the Venue'      => 'appropriateness_of_venue',
                    'Sound System and Hall Preparation' => 'sound_system_and_hall_preparation',
                    'Restroom/s'                        => 'restrooms',
                    'Food and Drinks'                   => 'food_and_drinks'
                ];

                foreach ($evaluationItems as $item) {
                    if (isset($evalMapping[$item['area']])) {
                        $evalData[$evalMapping[$item['area']]] = $item['rating'] ?: 0;
                    }
                }
                $db->table('accomplishment_evaluation_results')->insert($evalData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Transaction failed.');
            }

            \App\Models\ActivityLogModel::log($reportData['user_id'], 'Submit Document', 'submitted Accomplishment Report: ' . $reportData['activity_title']);

            return $this->respondCreated([
                'success' => true,
                'message' => 'Accomplishment report submitted successfully.'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->fail($e->getMessage());
        }
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $reports = $db->table('accomplishment_report as ar')
            ->select('ar.id, ar.user_id, ar.act_design_id, ar.control_number as control, ar.activity_title as title, ar.start_date as date, ar.status, u.username as office, u.username as username, u.email, ad.form_type as formLabel')
            ->join('users u', 'u.id = ar.user_id', 'left')
            ->join('activity_design ad', 'ad.act_design_id = ar.act_design_id', 'left')
            ->whereNotIn('ar.status', ['Completed', 'Verified'])
            ->where('ar.deleted_at', null)
            ->orderBy('ar.created_at', 'DESC')
            ->get()->getResultArray();

        // Fallback for form_type if activity_design is archived
        foreach ($reports as &$r) {
            if (empty($r['formLabel']) && !empty($r['act_design_id'])) {
                $archivedDesign = $db->table('archived_activity_designs')
                    ->where('original_act_design_id', $r['act_design_id'])
                    ->get()->getRowArray();
                if ($archivedDesign) {
                    $r['formLabel'] = $archivedDesign['form_type'];
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports
        ]);
    }

    public function getUserReports($userId = null)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        $reports = $db->table('accomplishment_report as ar')
            ->select('ar.id, ar.user_id, ar.act_design_id, ar.control_number as control, ar.activity_title as title, ar.start_date as date, ar.status, u.username as office, u.username as username, ad.form_type as formLabel')
            ->join('users u', 'u.id = ar.user_id', 'left')
            ->join('activity_design ad', 'ad.act_design_id = ar.act_design_id', 'left')
            ->whereNotIn('ar.status', ['Completed', 'Verified'])
            ->where('ar.user_id', $userId)
            ->where('ar.deleted_at', null)
            ->orderBy('ar.id', 'DESC')
            ->get()->getResultArray();

        // Fallback for form_type if activity_design is archived
        foreach ($reports as &$r) {
            if (empty($r['formLabel']) && !empty($r['act_design_id'])) {
                $archivedDesign = $db->table('archived_activity_designs')
                    ->where('original_act_design_id', $r['act_design_id'])
                    ->get()->getRowArray();
                if ($archivedDesign) {
                    $r['formLabel'] = $archivedDesign['form_type'];
                }
            }
        }

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

        $db = \Config\Database::connect();
        $reportModel = new AccomplishmentReportModel();
        
        $report = $reportModel
            ->select('accomplishment_report.*, office_units.office_name as office, users.full_name as submitter_name, accomplishment_report.start_date as date, gad_mandates.title as mandate_title, gender_issues.title as gender_issue_title, activity_classifications.classification_name')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('activity_design', 'activity_design.act_design_id = accomplishment_report.act_design_id', 'left')
            ->join('gad_mandates', 'gad_mandates.id = activity_design.gad_mandate_id', 'left')
            ->join('gender_issues', 'gender_issues.id = activity_design.gender_issue_id', 'left')
            ->join('activity_classifications', 'activity_classifications.id = activity_design.classification_id', 'left')
            ->where('accomplishment_report.id', $id)
            ->first();

        $isActive = true;
        if (!$report) {
            $report = $db->table('archived_accomplishment_reports as aar')
                ->select('aar.*, aar.original_report_id as id, aar.activity_title as title, office_units.office_name as office, users.full_name as submitter_name, aar.start_date as date, gm.title as mandate_title, gi.title as gender_issue_title, ac.classification_name')
                ->join('users', 'users.id = aar.user_id', 'left')
                ->join('office_units', 'office_units.office_id = users.office_id', 'left')
                ->join('activity_design as ad', 'ad.act_design_id = aar.act_design_id', 'left')
                ->join('gad_mandates as gm', 'gm.id = ad.gad_mandate_id', 'left')
                ->join('gender_issues as gi', 'gi.id = ad.gender_issue_id', 'left')
                ->join('activity_classifications as ac', 'ac.id = ad.classification_id', 'left')
                ->where('aar.original_report_id', $id)
                ->get()->getRowArray();

            if (!$report) {
                return $this->response->setJSON(['success' => false, 'message' => 'Accomplishment report not found'])->setStatusCode(404);
            }
            $isActive = false;
        }

        $reportId = $isActive ? ($report['id'] ?? $id) : ($report['original_report_id'] ?? $id);
        $table = $isActive ? 'accomplishment_budget_items' : 'archived_accomplishment_budget_items';
        $evalTable = $isActive ? 'accomplishment_evaluation_results' : 'archived_accomplishment_evaluation_results';

        $budgetItems = $db->table($table)->where('accomplishment_report_id', $reportId)->get()->getResultArray();
        $evalResults = $db->table($evalTable)->where('accomplishment_report_id', $reportId)->get()->getRowArray();

        $report['budget_items'] = $budgetItems;
        $report['evaluation_results'] = $evalResults ?: null;

        return $this->response->setJSON([
            'success' => true,
            'data'    => $report
        ]);
    }

    public function approveReport($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $report = $db->table('accomplishment_report')->where('id', $id)->get()->getRowArray();
        if (!$report) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report not found'])->setStatusCode(404);
        }

        $remarks = $this->request->getPost('remarks');
        $aDate = $this->request->getPost('assessment-date');

        $archiveData = [
            'original_report_id' => $report['id'],
            'control_number'     => $report['control_number'],
            'activity_title'     => $report['activity_title'],
            'start_date'         => $report['start_date'],
            'end_date'           => $report['end_date'],
            'start_time'         => $report['start_time'],
            'end_time'           => $report['end_time'],
            'venue'              => $report['venue'],
            'attendees'          => $report['attendees'],
            'male'               => $report['male'],
            'female'             => $report['female'],
            'rating'             => $report['rating'],
            'attachment'         => $report['attachment'],
            'user_id'            => $report['user_id'],
            'status'             => 'Completed',
            'remarks'            => $remarks,
            'assessment_date'    => $aDate
        ];
        
        // Insert into archived_accomplishment_reports
        $db->table('archived_accomplishment_reports')->insert($archiveData);

        // Copy accomplishment budget items to archived table before deleting
        $budgetItems = $db->table('accomplishment_budget_items')->where('accomplishment_report_id', $id)->get()->getResultArray();
        foreach ($budgetItems as $bi) {
            unset($bi['id']);
            $db->table('archived_accomplishment_budget_items')->insert($bi);
        }

        // Delete the original record from the active table to perform a true MOVE operation.
        $db->table('accomplishment_report')->where('id', $id)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archival transaction failed.'])->setStatusCode(500);
        }

        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $report['user_id'];
        \App\Models\ActivityLogModel::log($actionUserId, 'Approve Document', 'verified Accomplishment Report: ' . $report['activity_title']);

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Accomplishment report verified, set to Completed, and moved to archive.'
        ]);
    }

    public function revisionReport($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $remarks = $this->request->getPost('remarks');

        $updateData = [
            'status' => 'Revision Required',
            'remarks' => $remarks
        ];

        try {
            $db->table('accomplishment_report')->where('id', $id)->update($updateData);

            $item = $db->table('accomplishment_report')->where('id', $id)->get()->getRowArray();
            if ($item) {
                $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $item['user_id'];
                \App\Models\ActivityLogModel::log($actionUserId, 'Update Status', 'requested revision for Accomplishment Report: ' . $item['activity_title']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sent for revision successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function updateReport($id = null)
    {
        $reportModel = new AccomplishmentReportModel();
        $report = $reportModel->find($id);

        if (!$report) {
            return $this->failNotFound('Report not found');
        }

        $rules = [
            'control_number'  => 'required',
            'act_design_id'   => 'required|numeric',
            'activity_title'  => 'required',
            'start_date'      => 'required',
            'end_date'        => 'required',
            'start_time'      => 'required',
            'end_time'        => 'required',
            'venue'           => 'required',
            'attendees'       => 'required|numeric',
            'male'            => 'required|numeric',
            'female'          => 'required|numeric',
            'rating'          => 'required',
            'user_id'         => 'required|numeric',
            'budget_items'    => 'required',
            'evaluation_items'=> 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $files = $this->request->getFiles();
            $fileNames = json_decode($report['attachment'], true) ?: [];
            
            if (!empty($files['attachment'])) {
                foreach ($files['attachment'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileName = FileStorage::saveToDrafts($file);
                        $fileNames[] = $fileName;
                    }
                }
            }

            $reportData = [
                'control_number' => $this->request->getPost('control_number'),
                'act_design_id'  => $this->request->getPost('act_design_id'),
                'activity_title' => $this->request->getPost('activity_title'),
                'start_date'     => $this->request->getPost('start_date'),
                'end_date'       => $this->request->getPost('end_date'),
                'start_time'     => $this->request->getPost('start_time'),
                'end_time'       => $this->request->getPost('end_time'),
                'venue'          => $this->request->getPost('venue'),
                'male'           => $this->request->getPost('male'),
                'female'         => $this->request->getPost('female'),
                'attendees'      => $this->request->getPost('attendees'),
                'rating'         => $this->request->getPost('rating'),
                'attachment'     => json_encode($fileNames),
                'status'         => $this->request->getPost('status') ?: 'Pending'
            ];

            // Validate that actual spending does not exceed proposed budget of the activity design
            $actDesignId = $report['act_design_id'];
            if (!empty($actDesignId)) {
                $design = $db->table('archived_activity_designs')->where('original_act_design_id', $actDesignId)->get()->getRowArray();
                if (!$design) {
                    $design = $db->table('activity_design')->where('act_design_id', $actDesignId)->get()->getRowArray();
                }

                if ($design) {
                    $proposedBudget = (float) $design['proposed_budget'];
                    
                    // Sum the actual budget items being submitted
                    $budgetItems = json_decode($this->request->getPost('budget_items'), true);
                    $actualTotal = 0;
                    if (!empty($budgetItems)) {
                        foreach ($budgetItems as $item) {
                            $actualTotal += (float) ($item['amount'] ?? $item['total'] ?? 0);
                        }
                    }

                    if ($actualTotal > $proposedBudget) {
                        throw new \Exception("Actual spending (PHP " . number_format($actualTotal, 2) . ") exceeds the approved proposed budget limit (PHP " . number_format($proposedBudget, 2) . "). Please file an Activity Design Revision first to adjust the budget.");
                    }
                }
            }

            $reportModel->update($id, $reportData);

            // 3. Update Budget Items (Delete and Re-insert)
            $budgetItems = json_decode($this->request->getPost('budget_items'), true);
            if (!empty($budgetItems)) {
                $db->table('accomplishment_budget_items')->where('accomplishment_report_id', $id)->delete();
                foreach ($budgetItems as $item) {
                    $db->table('accomplishment_budget_items')->insert([
                        'accomplishment_report_id' => $id,
                        'category'                 => $item['category'] ?? 'Miscellaneous',
                        'item_name'                => $item['name'] ?? 'Other',
                        'sub_item'                 => $item['sub_item'] ?? null,
                        'pax'                      => isset($item['pax']) && $item['pax'] !== '' ? (int)$item['pax'] : null,
                        'amount'                   => isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : 0.00
                    ]);
                }
            }

            // 4. Update Evaluation Results (Delete and Re-insert)
            $evaluationItems = json_decode($this->request->getPost('evaluation_items'), true);
            if (!empty($evaluationItems)) {
                $db->table('accomplishment_evaluation_results')->where('accomplishment_report_id', $id)->delete();
                
                $evalData = ['accomplishment_report_id' => $id];
                $evalMapping = [
                    'Time Management'                   => 'time_management',
                    'Orderliness and Program Flow'      => 'orderliness_and_program_flow',
                    'Appropriateness of the Venue'      => 'appropriateness_of_venue',
                    'Sound System and Hall Preparation' => 'sound_system_and_hall_preparation',
                    'Restroom/s'                        => 'restrooms',
                    'Food and Drinks'                   => 'food_and_drinks'
                ];

                foreach ($evaluationItems as $item) {
                    if (isset($evalMapping[$item['area']])) {
                        $evalData[$evalMapping[$item['area']]] = $item['rating'] ?: 0;
                    }
                }
                $db->table('accomplishment_evaluation_results')->insert($evalData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Transaction failed.");
            }

            return $this->respond(['success' => true, 'message' => 'Report updated and resubmitted successfully.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->fail($e->getMessage());
        }
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
