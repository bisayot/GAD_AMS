<?php

namespace App\Controllers;

use App\Models\ActivityDesignModel;
use App\Libraries\FileStorage;

class ActivityDesignController extends BaseController
{
    public function submitDesign()
    {
        $activityDesignModel = new ActivityDesignModel();

        // Validation rules aligned with frontend FormData field names
        $rules = [
            "nature"              => "required",
            "activity_title"      => "required",
            "start_date"          => "required",
            "end_date"            => "required",
            "start_time"          => "required",
            "end_time"            => "required",
            "venue_id"            => "required|numeric",
            "target_participants" => "required|numeric",
            "proposed_budget"     => "required|numeric",
            "budget_items"        => "required",
            "user_id"             => "required",
            "design_file"         => "uploaded[design_file]|max_size[design_file,10240]|ext_in[design_file,pdf]",
        ];

        $messages = [
            "nature"              => ["required" => "Form type is required"],
            "activity_title"      => ["required" => "Activity title is required"],
            "start_date"          => ["required" => "Start date is required"],
            "end_date"            => ["required" => "End date is required"],
            "start_time"          => ["required" => "Start time is required"],
            "end_time"            => ["required" => "End time is required"],
            "venue_id"            => ["required" => "Venue is required", "numeric" => "Invalid venue format"],
            "target_participants" => [
                "required" => "Target participants is required",
                "numeric"  => "Target participants must be a number",
            ],
            "proposed_budget"     => [
                "required" => "Proposed budget is required",
                "numeric"  => "Proposed budget must be a numeric value",
            ],
            "budget_items"        => ["required" => "Budget items breakdown is required"],
            "user_id"             => ["required" => "User identification is missing"],
            "design_file"         => [
                "uploaded" => "Design file was not uploaded correctly",
                "max_size" => "Design file size exceeds the 10MB limit",
                "ext_in"   => "Design file must be a PDF",
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                "success" => false,
                "errors"  => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        try {
            // Save uploaded PDF to writable/uploads/drafts/
            $file = $this->request->getFile('design_file');
            $fileName = FileStorage::saveToDrafts($file);

            $data = [
                "form_type"           => $this->request->getPost("nature"),
                "activity_title"      => $this->request->getPost("activity_title"),
                "start_date"          => $this->request->getPost("start_date"),
                "end_date"            => $this->request->getPost("end_date"),
                "start_time"          => $this->request->getPost("start_time"),
                "end_time"            => $this->request->getPost("end_time"),
                "venue_id"            => $this->request->getPost("venue_id"),
                "target_participants" => $this->request->getPost("target_participants"),
                "proposed_budget"     => $this->request->getPost("proposed_budget"),
                "user_id"             => $this->request->getPost("user_id"),
                "attachment"          => $fileName,
                "status"              => "Pending",
            ];

            if (empty($data['user_id'])) {
                throw new \Exception("User ID is missing. Please log in again.");
            }

            if ($activityDesignModel->insert($data)) {
                $actDesignId = $activityDesignModel->getInsertID();

                // Save budget items
                $budgetItemsStr = $this->request->getPost("budget_items");
                if ($budgetItemsStr) {
                    $budgetItems = json_decode($budgetItemsStr, true);
                    if (is_array($budgetItems)) {
                        $budgetItemsModel = new \App\Models\ActivityBudgetItemsModel();
                        $budgetItems['act_design_id'] = $actDesignId;
                        $budgetItemsModel->insert($budgetItems);
                    }
                }

                return $this->response->setJSON([
                    "success" => true,
                    "message" => "Data saved successfully"
                ]);
            }

            return $this->response->setJSON([
                "success" => false,
                "message" => "Failed to save data into database.",
                "errors"  => $activityDesignModel->errors()
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
        
        $active = $db->table('activity_design as ad')
            ->select('ad.act_design_id, ad.status, cn.control_number as control, office_units.office_name as office, users.full_name as submitter_name, ad.activity_title as title, ad.form_type as formLabel, ad.start_date as date, ad.end_date')
            ->join('users', 'users.id = ad.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number as cn', 'cn.act_design_id = ad.act_design_id', 'left')
            ->where('ad.deleted_at', null)
            ->get()->getResultArray();

        usort($active, function($a, $b) {
            return $a['act_design_id'] <=> $b['act_design_id'];
        });

        return $this->response->setJSON([
            'success' => true,
            'data'    => $active
        ]);
    }


    public function getUserDesigns($userId = null)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        
        $active = $db->table('activity_design as ad')
            ->select('ad.act_design_id, ad.status, cn.control_number as control, office_units.office_name as office, users.full_name as submitter_name, ad.activity_title as title, ad.form_type as formLabel, ad.start_date as date, ad.end_date')
            ->join('users', 'users.id = ad.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number as cn', 'cn.act_design_id = ad.act_design_id', 'left')
            ->where('ad.user_id', $userId)
            ->where('ad.deleted_at', null)
            ->get()->getResultArray();

        usort($active, function($a, $b) {
            return $a['act_design_id'] <=> $b['act_design_id'];
        });

        return $this->response->setJSON([
            'success' => true,
            'data'    => $active
        ]);
    }

    public function show($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $activityDesignModel = new ActivityDesignModel();
        $design = $activityDesignModel
            ->select('activity_design.*, control_number.control_number as control, office_units.office_name as office, users.full_name as submitter_name, activity_design.start_date as date, venues.venue_name as venue')
            ->join('users', 'users.id = activity_design.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number', 'control_number.act_design_id = activity_design.act_design_id', 'left')
            ->join('venues', 'venues.venue_id = activity_design.venue_id', 'left')
            ->where('activity_design.act_design_id', $id)
            ->first();

        if (!$design) {
            // Try searching in archive fallback
            $db = \Config\Database::connect();
            $design = $db->table('archived_activity_designs as aad')
                ->select('aad.*, aad.original_act_design_id as act_design_id, aad.activity_title as title, aad.form_type as formLabel, office_units.office_name as office, users.full_name as submitter_name, aad.start_date as date, venues.venue_name as venue')
                ->join('users', 'users.id = aad.user_id', 'left')
                ->join('office_units', 'office_units.office_id = users.office_id', 'left')
                ->join('control_number as cn', 'cn.act_design_id = aad.original_act_design_id', 'left')
                ->join('venues', 'venues.venue_id = aad.venue_id', 'left')
                ->select('COALESCE(cn.control_number, "N/A") as control')
                ->where('aad.original_act_design_id', $id)
                ->get()->getRowArray();

            if (!$design) {
                return $this->response->setJSON(['success' => false, 'message' => 'Activity design not found'])->setStatusCode(404);
            }
            $design['is_archived'] = true;
        } else {
            $design['is_archived'] = false;
        }

        // Fetch budget items
        $budgetModel = new \App\Models\ActivityBudgetItemsModel();
        $budget = $budgetModel->where('act_design_id', $design['act_design_id'])->first();
        if ($budget) {
            $design = array_merge($design, $budget);
        }

        return $this->response->setJSON(['success' => true, 'data' => $design]);
    }

    public function getTWGSubmissions()
    {
        $db = \Config\Database::connect();

        $gadOfficeId = 1;
        $gadStaffOfficeId = 47;

        $offices = $db->table('office_units')
            ->select('office_id, office_name')
            ->where('office_id !=', $gadStaffOfficeId)
            ->orderBy('office_id', 'ASC')
            ->get()
            ->getResultArray();

        $users = $db->table('users')
            ->select('users.office_id, COALESCE(user_profiles.user_role, "Non-TWG") as user_role')
            ->join('user_profiles', 'user_profiles.user_id = users.id', 'left')
            ->select('( (SELECT COUNT(*) FROM activity_design WHERE activity_design.user_id = users.id) + (SELECT COUNT(*) FROM archived_activity_designs WHERE archived_activity_designs.user_id = users.id) ) as activity_designs_count')
            ->select('( (SELECT COUNT(*) FROM accomplishment_report WHERE accomplishment_report.user_id = users.id) + (SELECT COUNT(*) FROM archived_accomplishment_reports WHERE archived_accomplishment_reports.user_id = users.id) ) as accomplishment_reports_count')
            ->where('users.role !=', 'admin')
            ->get()
            ->getResultArray();

        $officeStats = [];
        foreach ($users as $u) {
            $officeId = (int) ($u['office_id'] ?? 0);
            if ($officeId === $gadStaffOfficeId) {
                $officeId = $gadOfficeId;
            }
            if ($officeId === 0) {
                continue;
            }

            if (! isset($officeStats[$officeId])) {
                $officeStats[$officeId] = [
                    'twg_count' => 0,
                    'nontwg_count' => 0,
                    'staff_count' => 0,
                    'activity_designs_count' => 0,
                    'accomplishment_reports_count' => 0,
                ];
            }

            $roleLower = strtolower($u['user_role']);
            if ($roleLower === 'twg') {
                $officeStats[$officeId]['twg_count']++;
            } elseif ($roleLower === 'staff') {
                $officeStats[$officeId]['staff_count']++;
            } else {
                $officeStats[$officeId]['nontwg_count']++;
            }

            $officeStats[$officeId]['activity_designs_count'] += (int) $u['activity_designs_count'];
            $officeStats[$officeId]['accomplishment_reports_count'] += (int) $u['accomplishment_reports_count'];
        }

        $data = [];
        $totalDesigns = 0;
        $totalReports = 0;
        $totalTWG = 0;
        $totalStaff = 0;
        $totalNonTWG = 0;

        foreach ($offices as $office) {
            $officeId = (int) $office['office_id'];
            $stats = $officeStats[$officeId] ?? [
                'twg_count' => 0,
                'nontwg_count' => 0,
                'staff_count' => 0,
                'activity_designs_count' => 0,
                'accomplishment_reports_count' => 0,
            ];

            $totalSubmissions = $stats['activity_designs_count'] + $stats['accomplishment_reports_count'];

            $data[] = [
                'id' => $officeId,
                'office_name' => $office['office_name'],
                'twg_count' => $stats['twg_count'],
                'nontwg_count' => $stats['nontwg_count'],
                'staff_count' => $stats['staff_count'],
                'activity_designs_count' => $stats['activity_designs_count'],
                'accomplishment_reports_count' => $stats['accomplishment_reports_count'],
                'total_submissions' => $totalSubmissions,
            ];

            $totalDesigns += $stats['activity_designs_count'];
            $totalReports += $stats['accomplishment_reports_count'];
            $totalTWG += $stats['twg_count'];
            $totalStaff += $stats['staff_count'];
            $totalNonTWG += $stats['nontwg_count'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'total' => count($data),
                'total_twg' => $totalTWG,
                'total_staff' => $totalStaff,
                'total_nontwg' => $totalNonTWG,
                'total_designs' => $totalDesigns,
                'total_reports' => $totalReports,
                'last_page' => 1
            ]
        ]);
    }

    public function getArchivedDesigns()
    {
        $activityDesignModel = new ActivityDesignModel();

        // Fetch designs that are 'Approved' or 'Cancelled'
        $designs = $activityDesignModel
            ->select('activity_design.*, control_number.control_number as control, office_units.office_name as office, users.full_name as submitter_name, activity_design.activity_title as title, activity_design.form_type as formLabel, activity_design.start_date as date')
            ->join('users', 'users.id = activity_design.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('control_number', 'control_number.act_design_id = activity_design.act_design_id', 'left')
            ->whereIn('activity_design.status', ['Approved', 'Cancelled'])
            ->orderBy('activity_design.act_design_id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $designs
        ]);
    }

    public function updateDesign($id)
    {
        // 1. Initialize the Model
        // Ensure you have "use App\Models\ActivityDesignModel;" at the top of your file
        $model = new \App\Models\ActivityDesignModel(); 
        
        $design = $model->find($id);
        if (!$design) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Activity design record #$id not found."
            ])->setStatusCode(404);
        }

        // 2. Collect only the fields that were sent in the request
        // Using array_filter ensures we don't overwrite existing data with nulls
        $data = [
            'activity_title'      => $this->request->getPost('activity_title'),
            'form_type'           => $this->request->getPost('form_type'),
            'start_date'          => $this->request->getPost('start_date'),
            'end_date'            => $this->request->getPost('end_date'),
            'start_time'          => $this->request->getPost('start_time'),
            'end_time'            => $this->request->getPost('end_time'),
            'venue'               => $this->request->getPost('venue'),
            'proposed_budget'     => $this->request->getPost('proposed_budget'),
            'target_participants' => $this->request->getPost('target_participants'),
            'status'              => $this->request->getPost('status') ?? 'Pending', // Force back to Pending
        ];

        // Remove null values so we only update what was provided in the form
        $updateData = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        // 3. Handle New File Upload (if any) — saves to drafts, removes old draft
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Remove the old draft file to avoid orphaned files
            if (!empty($design['attachment'])) {
                FileStorage::deleteFromDrafts($design['attachment']);
            }
            $newName = FileStorage::saveToDrafts($file);
            $updateData['attachment'] = $newName;
        }

        // 4. Execute Update
        try {
            if ($model->update($id, $updateData)) {

                // Update or Insert budget items
                $budgetItemsStr = $this->request->getPost("budget_items");
                if ($budgetItemsStr) {
                    $budgetItems = json_decode($budgetItemsStr, true);
                    if (is_array($budgetItems)) {
                        $budgetItemsModel = new \App\Models\ActivityBudgetItemsModel();
                        $existingBudget = $budgetItemsModel->where('act_design_id', $id)->first();
                        
                        // We use the primary key if it exists, otherwise insert
                        if ($existingBudget) {
                            $primaryKey = $budgetItemsModel->primaryKey;
                            $budgetItemsModel->update($existingBudget[$primaryKey], $budgetItems);
                        } else {
                            $budgetItems['act_design_id'] = $id;
                            $budgetItemsModel->insert($budgetItems);
                        }
                    }
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Activity Design updated and resubmitted successfully.'
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
     * Approve an Activity Design:
     *  1. Assigns control number & assessment metadata
     *  2. Updates status to 'Approved'
     *  3. Inserts into archived_activity_designs table
     *  4. Deletes from active activity_design table
     *  5. Moves the PDF from drafts → archived
     */
    public function approveDesign($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $body = $this->request->getJSON(true) ?? $this->request->getPost();
        $controlNumber        = $body['control_number']        ?? null;
        $assessmentDate       = $body['assessment_date']       ?? date('Y-m-d');
        $accomplishmentDeadline = $body['accomplishment_deadline'] ?? null;
        $remarks              = $body['remarks']               ?? '';

        if (!$controlNumber) {
            return $this->response->setJSON(['success' => false, 'message' => 'Control number is required'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $item = $db->table('activity_design')->where('act_design_id', $id)->get()->getRowArray();
        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Activity design not found'])->setStatusCode(404);
        }

        // 1. Save control number record
        $existingControl = $db->table('control_number')->where('act_design_id', $id)->get()->getRowArray();
        if ($existingControl) {
            $db->table('control_number')->where('act_design_id', $id)->update(['control_number' => $controlNumber]);
        } else {
            $db->table('control_number')->insert([
                'act_design_id'  => $id,
                'control_number' => $controlNumber
            ]);
        }

        // 2. Insert into archived_activity_designs
        $archiveData = [
            'original_act_design_id'   => $item['act_design_id'],
            'activity_title'           => $item['activity_title'],
            'start_date'               => $item['start_date'],
            'end_date'                 => $item['end_date'],
            'start_time'               => $item['start_time'],
            'end_time'                 => $item['end_time'],
            'status'                   => 'Approved',
            'attachment'               => $item['attachment'],
            'user_id'                  => $item['user_id'],
            'gpb_id'                   => $item['gpb_id'] ?? null,
            'venue_id'                 => $item['venue_id'],
            'target_participants'      => $item['target_participants'],
            'proposed_budget'          => $item['proposed_budget'],
            'form_type'                => $item['form_type'],
            'assessment_date'          => $assessmentDate,
            'accomplishment_deadline'  => $accomplishmentDeadline,
            'remarks'                  => $remarks,
        ];
        $db->table('archived_activity_designs')->insert($archiveData);

        // 3. Delete from active table
        $db->table('activity_design')->where('act_design_id', $id)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve and archive design'])->setStatusCode(500);
        }

        // 4. Move PDF from drafts → archived (outside transaction)
        FileStorage::moveToArchived($item['attachment']);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Activity Design approved and archived successfully.'
        ]);
    }

    public function revisionDesign($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $body = $this->request->getJSON(true) ?? $this->request->getPost();
        $remarks = $body['remarks'] ?? '';
        $deadline = $body['deadline'] ?? null;

        $db = \Config\Database::connect();
        
        $updateData = [
            'status' => 'Revision Required'
        ];
        
        // If remarks column exists in activity_design, it will be updated.
        // Even if it doesn't, we update status.
        try {
            $updateData['remarks'] = $remarks;
            if ($deadline) {
                $updateData['accomplishment_deadline'] = $deadline;
            }
            $db->table('activity_design')->where('act_design_id', $id)->update($updateData);
        } catch (\Exception $e) {
            // Fallback: If columns don't exist in active table, just update status.
            $db->table('activity_design')->where('act_design_id', $id)->update(['status' => 'Revision Required']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Sent for revision successfully'
        ]);
    }

    public function updateDeadline($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $body = $this->request->getJSON(true) ?? $this->request->getPost();
        $deadline = $body['deadline'] ?? null;
        $isArchived = filter_var($body['is_archived'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if (!$deadline) {
            return $this->response->setJSON(['success' => false, 'message' => 'Deadline required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $table = $isArchived ? 'archived_activity_designs' : 'activity_design';
        $idColumn = $isArchived ? 'original_act_design_id' : 'act_design_id';

        try {
            $updated = $db->table($table)->where($idColumn, $id)->update(['accomplishment_deadline' => $deadline]);
            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'Deadline updated successfully']);
            }
            return $this->response->setJSON(['success' => false, 'message' => 'No changes made or record not found']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function trash($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $model = new ActivityDesignModel();
        
        // Find if it exists first
        $design = $model->find($id);
        if (!$design) {
            return $this->response->setJSON(['success' => false, 'message' => 'Activity design not found'])->setStatusCode(404);
        }

        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Activity design moved to trash successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to move activity design to trash'])->setStatusCode(500);
    }
}