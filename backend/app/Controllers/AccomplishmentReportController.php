<?php

namespace App\Controllers;

use App\Models\AccomplishmentReportModel;
use CodeIgniter\API\ResponseTrait;

class AccomplishmentReportController extends BaseController
{
    use ResponseTrait;

    public function submitReport()
    {
        $db = \Config\Database::connect();
        $reportModel = new AccomplishmentReportModel();

        try {
            $db->transStart();

            // 1. Handle Multiple File Uploads
            $attachments = $this->request->getFileMultiple('attachment');
            $fileNames = [];
            
            if ($attachments) {
                foreach ($attachments as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads', $newName);
                        $fileNames[] = $newName;
                    }
                }
            }

            // 2. Prepare Main Report Data
            $reportData = [
                'control_number' => $this->request->getPost('control_number'),
                'act_design_id'  => $this->request->getPost('act_design_id'),
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
                'user_id'        => $this->request->getPost('user_id'),
                'attachment'     => json_encode($fileNames), // Store list of files as JSON
                'status'         => 'Pending'
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
            ->orderBy('ar.created_at', 'DESC')
            ->get()->getResultArray();

        // Fallback for form_type if activity_design is archived
        // This ensures that even if the original activity design is moved to archive,
        // the form_type can still be retrieved for the accomplishment report.
        foreach ($reports as &$report) {
            if (empty($report['formLabel']) && !empty($report['act_design_id'])) {
                $archive = $db->table('archived_activity_designs')
                    ->select('form_type')
                    ->where('original_act_design_id', $report['act_design_id'])
                    ->get()
                    ->getRowArray();
                
                if ($archive) {
                    $report['formLabel'] = $archive['form_type'];
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'data' => $reports]);
    }

    public function getUserReports($userId = null)
    {
        $db = \Config\Database::connect();

        // 1. Fetch from main table
        $active = $db->table('accomplishment_report as ar')
            ->select('ar.*, ar.activity_title as title, ar.control_number as control, ar.start_date as date, ad.form_type')
            ->join('activity_design ad', 'ad.act_design_id = ar.act_design_id', 'left')
            ->whereNotIn('ar.status', ['Completed', 'Verified'])
            ->where('ar.user_id', $userId)
            ->orderBy('ar.created_at', 'DESC')
            ->get()->getResultArray();

        // Fallback for form_type if activity_design is archived
        foreach ($active as &$report) {
            if (empty($report['form_type']) && !empty($report['act_design_id'])) {
                $archive = $db->table('archived_activity_designs')
                    ->select('form_type')
                    ->where('original_act_design_id', $report['act_design_id'])
                    ->get()
                    ->getRowArray();
                
                if ($archive) {
                    $report['form_type'] = $archive['form_type'];
                }
            }
        }

        return $this->respond(['success' => true, 'data' => $active]);
    }

    public function show($id = null)
    {
        $db = \Config\Database::connect();
        
        // Perform JOINS without budget items table
        $report = $db->table('accomplishment_report as ar')
            ->select('ar.*, aer.*, u.username, u.email, u.username as office, ad.form_type')
            ->join('users u', 'u.id = ar.user_id', 'left')
            ->join('accomplishment_evaluation_results aer', 'aer.accomplishment_report_id = ar.id', 'left')
            ->join('activity_design ad', 'ad.act_design_id = ar.act_design_id', 'left')
            ->where('ar.id', $id)
            ->get()
            ->getRowArray();

        $isActive = true;
        if (!$report) {
            $report = $db->table('archived_accomplishment_reports as ar')
                ->select('ar.*, aer.*, u.username, u.email, u.username as office, ad.form_type')
                ->join('users u', 'u.id = ar.user_id', 'left')
                ->join('accomplishment_evaluation_results aer', 'aer.accomplishment_report_id = ar.original_report_id', 'left')
                ->join('activity_design ad', 'ad.act_design_id = ar.act_design_id', 'left')
                ->where('ar.original_report_id', $id)
                ->get()
                ->getRowArray();
            $isActive = false;
        }

        if (!$report) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accomplishment report not found'])->setStatusCode(404);
        }

        // Fallback: If form_type is empty (design might be archived), check archived_activity_designs
        if (empty($report['form_type'])) {
            $archive = $db->table('archived_activity_designs')
                ->select('form_type')
                ->where('original_act_design_id', $report['act_design_id'])
                ->get()
                ->getRowArray();
            
            if ($archive) {
                $report['form_type'] = $archive['form_type'];
            }
        }

        // Fetch proposed_budget_limit
        if (!empty($report['act_design_id'])) {
            $design = $db->table('archived_activity_designs')
                ->select('proposed_budget')
                ->where('original_act_design_id', $report['act_design_id'])
                ->get()
                ->getRowArray();
            
            if (!$design) {
                $design = $db->table('activity_design')
                    ->select('proposed_budget')
                    ->where('act_design_id', $report['act_design_id'])
                    ->get()
                    ->getRowArray();
            }
            
            if ($design) {
                $report['proposed_budget_limit'] = $design['proposed_budget'];
            }
        }

        // Fetch normalized budget items
        $reportId = $isActive ? ($report['id'] ?? $id) : ($report['original_report_id'] ?? $id);
        $table = $isActive ? 'accomplishment_budget_items' : 'archived_accomplishment_budget_items';
        
        $budgetItems = $db->table($table)->where('accomplishment_report_id', $reportId)->get()->getResultArray();

        // Populate virtual columns for backward compatibility
        $report['meals_and_snacks'] = 0;
        $report['function_room_venue'] = 0;
        $report['accommodation'] = 0;
        $report['equipment_rental'] = 0;
        $report['professional_fee_honoria'] = 0;
        $report['tokens'] = 0;
        $report['materials_and_supplies'] = 0;
        $report['transportation'] = 0;
        $report['others'] = 0;

        foreach ($budgetItems as $bi) {
            $amt = (float)$bi['amount'];
            if ($bi['item_name'] === 'Meals' || $bi['item_name'] === 'Snacks' || $bi['item_name'] === 'Meals and Snacks (AM/PM)') {
                $report['meals_and_snacks'] += $amt;
            } elseif ($bi['item_name'] === 'Function Room/Venue') {
                $report['function_room_venue'] += $amt;
            } elseif ($bi['item_name'] === 'Accommodation') {
                $report['accommodation'] += $amt;
            } elseif ($bi['item_name'] === 'Equipment Rental') {
                $report['equipment_rental'] += $amt;
            } elseif ($bi['item_name'] === 'Professional Fee/Honoria' || $bi['item_name'] === 'Professional Fee/Honoria') {
                $report['professional_fee_honoria'] += $amt;
            } elseif ($bi['item_name'] === 'Token/s') {
                $report['tokens'] += $amt;
            } elseif ($bi['item_name'] === 'Materials and Supplies') {
                $report['materials_and_supplies'] += $amt;
            } elseif ($bi['item_name'] === 'Transportation') {
                $report['transportation'] += $amt;
            } elseif ($bi['item_name'] === 'Others') {
                $report['others'] += $amt;
            }
        }

        $report['budget_items'] = $budgetItems;

        return $this->response->setJSON(['success' => true, 'data' => $report]);
    }

    public function approveReport($id = null)
    {
        $db = \Config\Database::connect();
        $reportModel = new AccomplishmentReportModel();
        
        $report = $reportModel->find($id);
        if (!$report) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report not found'])->setStatusCode(404);
        }

        try {
            $db->transStart();

            $remarks = $this->request->getPost('remarks') ?: '';
            $aDate = $this->request->getPost('assessment_date') ?: date('Y-m-d');

            // 1. Prepare data for archive (Explicitly mapping to avoid column mismatch errors)
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
            
            // Insert into archived_accomplishment_reports (plural as per SQL dump)
            $db->table('archived_accomplishment_reports')->insert($archiveData);

            // Copy accomplishment budget items to archived table before deleting
            $budgetItems = $db->table('accomplishment_budget_items')->where('accomplishment_report_id', $id)->get()->getResultArray();
            foreach ($budgetItems as $bi) {
                unset($bi['id']);
                $db->table('archived_accomplishment_budget_items')->insert($bi);
            }

            // 2. Delete the original record from the active table to perform a true MOVE operation.
            // This prevents duplication where the record appears in both active and archive lists.
            $reportModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Archival transaction failed.'])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Accomplishment report verified, set to Completed, and moved to archive.'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()])->setStatusCode(500);
        }
    }

    public function revisionReport($id = null)
    {
        $reportModel = new AccomplishmentReportModel();
        
        $data = [
            'status'          => 'Revision',
            'assessment_date' => $this->request->getVar('assessment_date'),
            'remarks'         => $this->request->getVar('remarks'),
        ];

        if ($reportModel->update($id, $data)) {
            return $this->respond(['success' => true, 'message' => 'Revision requested successfully.']);
        }

        return $this->fail('Failed to request revision.');
    }

    public function verifyReport($id = null)
    {
        $db = \Config\Database::connect();
        $reportModel = new AccomplishmentReportModel();
        $report = $reportModel->find($id);

        if (!$report) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report not found'])->setStatusCode(404);
        }

        try {
            $db->transStart();
            $remarks = $this->request->getVar('remarks') ?: '';
            $aDate = $this->request->getVar('assessment_date') ?: date('Y-m-d');

            // Archive before marking as terminal in main table
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
                'status'             => 'Verified',
                'remarks'            => $remarks,
                'assessment_date'    => $aDate
            ];
            $db->table('archived_accomplishment_reports')->insert($archiveData);

            // Copy accomplishment budget items to archived table before deleting
            $budgetItems = $db->table('accomplishment_budget_items')->where('accomplishment_report_id', $id)->get()->getResultArray();
            foreach ($budgetItems as $bi) {
                unset($bi['id']);
                $db->table('archived_accomplishment_budget_items')->insert($bi);
            }

            // 2. Delete the original record from the active table to perform a true MOVE operation.
            $reportModel->delete($id);

            $db->transComplete();
            return $this->respond(['success' => true, 'message' => 'Report verified and moved to archive.']);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function updateReport($id = null)
    {
        $reportModel = new AccomplishmentReportModel();
        $db = \Config\Database::connect();

        $report = $reportModel->find($id);
        if (!$report) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report not found'])->setStatusCode(404);
        }

        try {
            $db->transStart();

            // 1. Handle File Uploads (Existing + New)
            $existing = json_decode($this->request->getPost('existing_attachments'), true) ?: [];
            $attachments = $this->request->getFileMultiple('attachment');
            $fileNames = $existing;
            
            if ($attachments) {
                foreach ($attachments as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(FCPATH . 'uploads', $newName);
                        $fileNames[] = $newName;
                    }
                }
            }

            // 2. Prepare Update Data
            $reportData = [
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
            return $this->respond(['success' => true, 'message' => 'Report updated and resubmitted successfully.']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->fail($e->getMessage());
        }
    }
}