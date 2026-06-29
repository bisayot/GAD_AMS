<?php

namespace App\Controllers;

use App\Models\ActivityDesignModel;
use App\Models\ApprovedControlModel;
use App\Libraries\FileStorage;

class ActivityDesignController extends BaseController
{
    public function submitDesign()
    {
        $activityDesignModel = new ActivityDesignModel();

        $rules = [
            "form_type"           => "required",
            "activity_title"      => "required",
            "start_date"          => "required",
            "end_date"            => "required",
            "start_time"          => "required",
            "end_time"            => "required",
            "venue_id"            => "required",
            "target_participants" => "required|numeric",
            "proposed_budget"     => "required|numeric",
            "budget_items"        => "required",
            "user_id"             => "required",
            "attachment"          => "uploaded[attachment]|max_size[attachment,10240]|ext_in[attachment,pdf]",
        ];

        $messages = [
            "form_type"           => ["required" => "Form type is required"],
            "activity_title"      => ["required" => "Activity title is required"],
            "start_date"          => ["required" => "Start date is required"],
            "end_date"            => ["required" => "End date is required"],
            "start_time"          => ["required" => "Start time is required"],
            "end_time"            => ["required" => "End time is required"],
            "venue_id"            => ["required" => "Venue is required"],
            "target_participants" => [
                "required" => "Target participants is required",
                "numeric"  => "Target participants must be a number",
            ],
            "proposed_budget"     => [
                "required" => "Proposed budget is required",
                "numeric"  => "Proposed budget must be a numeric value",
            ],
            "budget_items"        => ["required" => "Budget items are required"],
            "user_id"             => ["required" => "User identification is missing"],
            "attachment"          => [
                "required" => "Design file is required",
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
            $db = \Config\Database::connect();
            $db->transStart();

            $venueId = $this->request->getPost("venue_id");
            if ($venueId === 'Other') {
                $customVenueName = $this->request->getPost("custom_venue");
                if (empty($customVenueName)) {
                    return $this->response->setJSON([
                        "success" => false,
                        "errors"  => ["custom_venue" => "Custom venue name is required"]
                    ])->setStatusCode(422);
                }

                // Insert new venue
                $venueModel = new \App\Models\VenueModel();
                $venueModel->insert(['venue_name' => $customVenueName]);
                $venueId = $venueModel->getInsertID();
            }

            $gadMandateId = $this->request->getPost('gad_mandate_id');
            if ($gadMandateId === 'Other' || $gadMandateId === 'new') {
                $customMandate = $this->request->getPost('custom_gad_mandate');
                $db = \Config\Database::connect();
                $db->table('gad_mandates')->insert([
                    'code' => 'CUSTOM',
                    'title' => $customMandate
                ]);
                $gadMandateId = $db->insertID();
            }

            $genderIssueId = $this->request->getPost('gender_issue_id');
            if ($genderIssueId === 'Other' || $genderIssueId === 'new') {
                $customIssue = $this->request->getPost('custom_gender_issue');
                $db = \Config\Database::connect();
                $db->table('gender_issues')->insert([
                    'mandate_id' => $gadMandateId,
                    'title' => $customIssue,
                    'gad_objective' => null
                ]);
                $genderIssueId = $db->insertID();
            }

            // Save uploaded PDF to writable/uploads/drafts/
            $file = $this->request->getFile('attachment');
            $fileName = FileStorage::saveToDrafts($file);

            $data = [
                "form_type"                  => $this->request->getPost("form_type"),
                "activity_classification_id" => $this->request->getPost("activity_classification_id"),
                "classification_id"          => $this->request->getPost("activity_classification_id"), // mapping to db column
                "gad_mandate_id"             => $gadMandateId,
                "gender_issue_id"            => $genderIssueId,
                "activity_title"             => $this->request->getPost("activity_title"),
                "start_date"                 => $this->request->getPost("start_date"),
                "end_date"                   => $this->request->getPost("end_date"),
                "start_time"                 => $this->request->getPost("start_time"),
                "end_time"                   => $this->request->getPost("end_time"),
                "venue_id"                   => $venueId,
                "target_participants"        => $this->request->getPost("target_participants"),
                "proposed_budget"            => $this->request->getPost("proposed_budget"),
                "user_id"                    => $this->request->getPost("user_id"),
                "attachment"                 => $fileName,
                "status"                     => "Pending",
            ];
            unset($data['activity_classification_id']); // remove the temporary mapping key

            if (empty($data['user_id'])) {
                throw new \Exception("User ID is missing. Please log in again.");
            }

            $insertId = $activityDesignModel->insert($data);
            
            if ($insertId) {
                $budgetItemsStr = $this->request->getPost("budget_items");
                if ($budgetItemsStr) {
                    $budgetItems = json_decode($budgetItemsStr, true);
                    if (is_array($budgetItems)) {
                        foreach ($budgetItems as $item) {
                            $db->table('activity_budget_items')->insert([
                                'act_design_id' => $insertId,
                                'category'      => $item['category'] ?? 'Miscellaneous',
                                'item_name'     => $item['name'] ?? 'Other',
                                'sub_item'      => $item['sub_item'] ?? null,
                                'pax'           => isset($item['pax']) && $item['pax'] !== '' ? (int)$item['pax'] : null,
                                'amount'        => isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : 0.00
                            ]);
                        }
                    }
                }

                $db->transComplete();

                if ($db->transStatus() === true) {
                    \App\Models\ActivityLogModel::log($data['user_id'], 'Submit Document', 'submitted Activity Design: ' . $data['activity_title']);

                    return $this->response->setJSON([
                        "success" => true,
                        "message" => "Data saved successfully"
                    ]);
                }
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
        $activityDesignModel = new ActivityDesignModel();

        $designs = $activityDesignModel
            ->select('activity_design.*, control_number.control_number as control, users.username as office, users.username as username, activity_design.activity_title as title, activity_design.form_type as formLabel, activity_design.start_date as date, COALESCE(venues.venue_name, activity_design.venue) as venue')
            ->join('users', 'users.id = activity_design.user_id', 'left')
            ->join('venues', 'venues.venue_id = activity_design.venue_id', 'left')
            ->join('control_number', 'control_number.act_design_id = activity_design.act_design_id', 'left')
            ->whereNotIn('activity_design.status', ['Approved', 'Cancelled'])
            ->where('activity_design.deleted_at', null)
            ->orderBy('activity_design.act_design_id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $designs
        ]);
    }

    public function getUserDesigns($userId = null)
    {
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        $active = $db->table('activity_design as ad')
            ->select('ad.*, cn.control_number as control, u.username as office, ad.activity_title as title, ad.form_type as formLabel, ad.start_date as date, COALESCE(v.venue_name, ad.venue) as venue')
            ->join('users u', 'u.id = ad.user_id', 'left')
            ->join('venues v', 'v.venue_id = ad.venue_id', 'left')
            ->join('control_number cn', 'cn.act_design_id = ad.act_design_id', 'left')
            ->whereNotIn('ad.status', ['Approved', 'Cancelled'])
            ->where('ad.user_id', $userId)
            ->where('ad.deleted_at', null)
            ->get()->getResultArray();

        usort($active, function($a, $b) {
            return (int)$b['act_design_id'] - (int)$a['act_design_id'];
        });

        return $this->response->setJSON(['success' => true, 'data' => $active]);
    }

    public function show($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $activityDesignModel = new ActivityDesignModel();
        $design = $activityDesignModel
            ->select('activity_design.*, control_number.control_number as control, office_units.office_name as office, users.full_name as submitter_name, activity_design.start_date as date, COALESCE(venues.venue_name, activity_design.venue) as venue, gad_mandates.title as mandate_title, gender_issues.title as gender_issue_title, activity_classifications.classification_name')
            ->join('users', 'users.id = activity_design.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('venues', 'venues.venue_id = activity_design.venue_id', 'left')
            ->join('gad_mandates', 'gad_mandates.id = activity_design.gad_mandate_id', 'left')
            ->join('gender_issues', 'gender_issues.id = activity_design.gender_issue_id', 'left')
            ->join('activity_classifications', 'activity_classifications.id = activity_design.classification_id', 'left')
            ->join('control_number', 'control_number.act_design_id = activity_design.act_design_id', 'left')
            ->where('activity_design.act_design_id', $id)
            ->first();

        $isActive = true;
        if (!$design) {
            $design = $db->table('archived_activity_designs as aad')
                ->select('aad.*, aad.original_act_design_id as act_design_id, aad.activity_title as title, aad.form_type as formLabel, office_units.office_name as office, users.full_name as submitter_name, aad.start_date as date, COALESCE(v.venue_name, aad.venue) as venue, COALESCE(cn.control_number, "N/A") as control, gm.title as mandate_title, gi.title as gender_issue_title, ac.classification_name')
                ->join('users', 'users.id = aad.user_id', 'left')
                ->join('office_units', 'office_units.office_id = users.office_id', 'left')
                ->join('control_number as cn', 'cn.act_design_id = aad.original_act_design_id', 'left')
                ->join('venues as v', 'v.venue_id = aad.venue_id', 'left')
                ->join('gad_mandates as gm', 'gm.id = aad.gad_mandate_id', 'left')
                ->join('gender_issues as gi', 'gi.id = aad.gender_issue_id', 'left')
                ->join('activity_classifications as ac', 'ac.id = aad.classification_id', 'left')
                ->where('aad.original_act_design_id', $id)
                ->get()->getRowArray();

            if (!$design) {
                return $this->response->setJSON(['success' => false, 'message' => 'Activity design not found'])->setStatusCode(404);
            }
            $isActive = false;
        }

        $actDesignId = $isActive ? ($design['act_design_id'] ?? $id) : ($design['original_act_design_id'] ?? $id);
        $table = $isActive ? 'activity_budget_items' : 'archived_activity_budget_items';
        
        $budgetItems = $db->table($table)->where('act_design_id', $actDesignId)->get()->getResultArray();
        
        // Populate virtual columns for backward compatibility
        $design['meals_and_snacks'] = 0;
        $design['function_room_venue'] = 0;
        $design['accommodation'] = 0;
        $design['equipment_rental'] = 0;
        $design['professional_fee_honoria'] = 0;
        $design['tokens'] = 0;
        $design['materials_and_supplies'] = 0;
        $design['transportation'] = 0;
        $design['others'] = 0;

        foreach ($budgetItems as $bi) {
            $amt = (float)$bi['amount'];
            if ($bi['item_name'] === 'Meals' || $bi['item_name'] === 'Snacks' || $bi['item_name'] === 'Meals and Snacks (AM/PM)') {
                $design['meals_and_snacks'] += $amt;
            } elseif ($bi['item_name'] === 'Function Room/Venue') {
                $design['function_room_venue'] += $amt;
            } elseif ($bi['item_name'] === 'Accommodation') {
                $design['accommodation'] += $amt;
            } elseif ($bi['item_name'] === 'Equipment Rental') {
                $design['equipment_rental'] += $amt;
            } elseif ($bi['item_name'] === 'Professional Fee/Honoria' || $bi['item_name'] === 'Professional Fee/Honoria') {
                $design['professional_fee_honoria'] += $amt;
            } elseif ($bi['item_name'] === 'Token/s') {
                $design['tokens'] += $amt;
            } elseif ($bi['item_name'] === 'Materials and Supplies') {
                $design['materials_and_supplies'] += $amt;
            } elseif ($bi['item_name'] === 'Transportation') {
                $design['transportation'] += $amt;
            } elseif ($bi['item_name'] === 'Others') {
                $design['others'] += $amt;
            }
        }

        $design['budget_items'] = $budgetItems;

        return $this->response->setJSON(['success' => true, 'data' => $design]);
    }

    public function getTWGSubmissions()
    {
        $db = \Config\Database::connect();
        
        $users = $db->table('users')
            ->select('id, username, role')
            ->select('(
                (SELECT COUNT(*) FROM activity_design WHERE activity_design.user_id = users.id) + 
                (SELECT COUNT(*) FROM archived_activity_designs WHERE archived_activity_designs.user_id = users.id)
            ) as activity_designs_count')
            ->select('(
                (SELECT COUNT(*) FROM accomplishment_report WHERE accomplishment_report.user_id = users.id) + 
                (SELECT COUNT(*) FROM archived_accomplishment_reports WHERE archived_accomplishment_reports.user_id = users.id)
            ) as accomplishment_reports_count')
            ->where('role !=', 'admin')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $totalDesigns = 0;
        $totalReports = 0;
        
        foreach ($users as &$u) {
            $u['activity_designs_count'] = (int)$u['activity_designs_count'];
            $u['accomplishment_reports_count'] = (int)$u['accomplishment_reports_count'];
            $u['total_submissions'] = $u['activity_designs_count'] + $u['accomplishment_reports_count'];
            
            $totalDesigns += $u['activity_designs_count'];
            $totalReports += $u['accomplishment_reports_count'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $users,
            'meta'    => [
                'total' => count($users),
                'total_designs' => $totalDesigns,
                'total_reports' => $totalReports,
                'last_page' => 1
            ]
        ]);
    }

    public function getArchivedDesigns()
    {
        $db = \Config\Database::connect();

        $designs = $db->table('archived_activity_designs as aad')
            ->select('aad.*, aad.original_act_design_id as act_design_id, cn.control_number as control, u.username as office, u.username as username, aad.activity_title as title, aad.form_type as formLabel, aad.start_date as date, COALESCE(v.venue_name, aad.venue) as venue')
            ->join('users u', 'u.id = aad.user_id', 'left')
            ->join('venues v', 'v.venue_id = aad.venue_id', 'left')
            ->join('control_number cn', 'cn.act_design_id = aad.original_act_design_id', 'left')
            ->orderBy('aad.archived_at', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $designs
        ]);
    }

    public function updateDesign($id)
    {
        $model = new ActivityDesignModel(); 
        
        $design = $model->find($id);
        if (!$design) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Activity design record #$id not found."
            ])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $venueId = $this->request->getPost("venue_id");
        $venueName = $this->request->getPost("venue_name");

        if (empty($venueId) && !empty($venueName)) {
            $vTable = $db->table('venues');
            $existing = $vTable->where('venue_name', $venueName)->get()->getRowArray();
            if ($existing) { 
                $venueId = $existing['venue_id'];
            } else {
                $vTable->insert(['venue_name' => $venueName]);
                $venueId = $db->insertID();
            }
        }

        $gadMandateId = $this->request->getPost('gad_mandate_id');
        if ($gadMandateId === 'Other' || $gadMandateId === 'new') {
            $customMandate = $this->request->getPost('custom_gad_mandate');
            $db->table('gad_mandates')->insert([
                'code' => 'CUSTOM',
                'title' => $customMandate
            ]);
            $gadMandateId = $db->insertID();
        }

        $genderIssueId = $this->request->getPost('gender_issue_id');
        if ($genderIssueId === 'Other' || $genderIssueId === 'new') {
            $customIssue = $this->request->getPost('custom_gender_issue');
            $db->table('gender_issues')->insert([
                'mandate_id' => $gadMandateId,
                'title' => $customIssue,
                'gad_objective' => null
            ]);
            $genderIssueId = $db->insertID();
        }

        $data = [
            'activity_title'      => $this->request->getPost('activity_title'),
            'form_type'           => $this->request->getPost('form_type') ?? $this->request->getPost('nature'),
            'classification_id'   => $this->request->getPost('activity_classification_id'),
            'gad_mandate_id'      => $gadMandateId ?? $this->request->getPost('gad_mandate_id'),
            'gender_issue_id'     => $genderIssueId ?? $this->request->getPost('gender_issue_id'),
            'start_date'          => $this->request->getPost('start_date'),
            'end_date'            => $this->request->getPost('end_date'),
            'start_time'          => $this->request->getPost('start_time'),
            'end_time'            => $this->request->getPost('end_time'),
            'venue'               => $this->request->getPost('venue'),
            'venue_id'            => $venueId ?? $this->request->getPost('venue_id'),
            'proposed_budget'     => $this->request->getPost('proposed_budget'),
            'target_participants' => $this->request->getPost('target_participants'),
            'status'              => $this->request->getPost('status') ?? 'Pending', 
        ];

        $updateData = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = FileStorage::saveToDrafts($file);
            $updateData['attachment'] = $fileName;
        }

        try {
            $db->transStart();
            if ($model->update($id, $updateData)) {
                $budgetItemsStr = $this->request->getPost("budget_items");
                if ($budgetItemsStr) {
                    $budgetItems = json_decode($budgetItemsStr, true);
                    if (is_array($budgetItems)) {
                        $db->table('activity_budget_items')->where('act_design_id', $id)->delete();
                        foreach ($budgetItems as $item) {
                            $db->table('activity_budget_items')->insert([
                                'act_design_id' => $id,
                                'category'      => $item['category'] ?? 'Miscellaneous',
                                'item_name'     => $item['name'] ?? 'Other',
                                'sub_item'      => $item['sub_item'] ?? null,
                                'pax'           => isset($item['pax']) && $item['pax'] !== '' ? (int)$item['pax'] : null,
                                'amount'        => isset($item['amount']) && $item['amount'] !== '' ? (float)$item['amount'] : 0.00
                            ]);
                        }
                    }
                }
                
                $db->transComplete();
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

    public function getVenues()
    {
        $db = \Config\Database::connect();
        $venues = $db->table('venues')->orderBy('venue_name', 'ASC')->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $venues
        ]);
    }

    public function approveDesign($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $item = $db->table('activity_design')->where('act_design_id', $id)->get()->getRowArray();
        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design not found'])->setStatusCode(404);
        }

        $controlNum = $this->request->getPost('control');
        $assessmentDate = $this->request->getPost('assessment-date');
        $deadline = $this->request->getPost('accomplishment-deadline');
        $remarks = $this->request->getPost('remarks');

        $archiveData = [
            'original_act_design_id' => $item['act_design_id'],
            'activity_title'         => $item['activity_title'],
            'start_date'             => $item['start_date'],
            'end_date'               => $item['end_date'],
            'start_time'             => $item['start_time'],
            'end_time'               => $item['end_time'],
            'status'                 => 'Approved',
            'remarks'                => $remarks,
            'assessment_date'        => $assessmentDate,
            'accomplishment_deadline' => $deadline,
            'attachment'             => $item['attachment'],
            'user_id'                => $item['user_id'],
            'gpb_id'                 => $item['gpb_id'] ?? null,
            'venue'                  => $item['venue'],
            'venue_id'               => $item['venue_id'],
            'target_participants'    => $item['target_participants'],
            'proposed_budget'        => $item['proposed_budget'],
            'form_type'              => $item['form_type']
        ];
        $db->table('archived_activity_designs')->insert($archiveData);

        // Copy budget items to archived_activity_budget_items before deleting
        $budgetItems = $db->table('activity_budget_items')->where('act_design_id', $id)->get()->getResultArray();
        foreach ($budgetItems as $bi) {
            unset($bi['id']);
            $db->table('archived_activity_budget_items')->insert($bi);
        }

        $db->table('control_number')->where('act_design_id', $id)->delete();
        $db->table('control_number')->insert([
            'control_number' => $controlNum,
            'act_design_id'  => $id,
            'user_id'        => $item['user_id']
        ]);

        // Delete the original record from the active table to perform a true MOVE operation.
        $db->table('activity_design')->where('act_design_id', $id)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve and archive design'])->setStatusCode(500);
        }

        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $item['user_id'];
        \App\Models\ActivityLogModel::log($actionUserId, 'Approve Document', 'approved Activity Design: ' . $item['activity_title']);

        // Move PDF from drafts -> archived (outside transaction)
        FileStorage::moveToArchived($item['attachment']);

        return $this->response->setJSON(['success' => true, 'message' => 'Design approved and archived.']);
    }

    public function getControlNumbers($userId = null)
    {
        $model = new ApprovedControlModel();
        $data = $model->getApprovedControlsWithActivityDetails($userId ? (int)$userId : null);
        return $this->response->setJSON([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function revisionDesign($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Design ID required'])->setStatusCode(400);
        }

        $model = new ActivityDesignModel();
        $db = \Config\Database::connect();

        $remarks = $this->request->getPost('remarks');
        $assessmentDate = $this->request->getPost('assessment-date');
        $accomplishmentDeadline = $this->request->getPost('accomplishment-deadline');
        $controlNum = $this->request->getPost('control');
        $status = $this->request->getPost('status') ?? 'Revision';

        try {
            $db->transStart();

            $db->table('activity_design')->where('act_design_id', $id)->update([
                'status'  => $status,
                'remarks' => $remarks,
                'assessment_date' => $assessmentDate,
                'accomplishment_deadline' => $accomplishmentDeadline
            ]);

            if (!empty($controlNum)) {
                $controlTable = $db->table('control_number');
                $existingControl = $controlTable->where('act_design_id', $id)->get()->getRow();

                if ($existingControl) {
                    $controlTable->where('act_design_id', $id)->update(['control_number' => $controlNum]);
                } else {
                    $design = $model->find($id);
                    $controlTable->insert([
                        'control_number' => $controlNum,
                        'act_design_id'  => $id,
                        'user_id'        => $design['user_id']
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Database transaction failed.");
            }

            $item = $db->table('activity_design')->where('act_design_id', $id)->get()->getRowArray();
            if ($item) {
                $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $item['user_id'];
                \App\Models\ActivityLogModel::log($actionUserId, 'Update Status', 'requested revision for Activity Design: ' . $item['activity_title']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'The design has been returned to the submitter for revision.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
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
                $item = $db->table($table)->where($idColumn, $id)->get()->getRowArray();
                if ($item) {
                    $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $item['user_id'];
                    \App\Models\ActivityLogModel::log($actionUserId, 'Update Deadline', 'updated accomplishment deadline for Activity Design: ' . $item['activity_title']);
                }
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
            $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $design['user_id'];
            \App\Models\ActivityLogModel::log($actionUserId, 'Trash Document', 'moved to trash Activity Design: ' . $design['activity_title']);
            return $this->response->setJSON(['success' => true, 'message' => 'Activity design moved to trash successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to move activity design to trash'])->setStatusCode(500);
    }

    public function getFormTypes()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('form_types');
        $query = $builder->get();
        return $this->response->setJSON($query->getResult());
    }

    public function getGADMandates()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('gad_mandates');
        $query = $builder->get();
        return $this->response->setJSON($query->getResult());
    }

    public function getGenderIssues($mandate_id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('gender_issues');

        if ($mandate_id) {
            $builder->where('mandate_id', $mandate_id);
        }

        $query = $builder->get();
        return $this->response->setJSON($query->getResult());
    }

    public function getActivityClassifications()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('activity_classifications');
        $query = $builder->get();
        return $this->response->setJSON($query->getResult());
    }
}
