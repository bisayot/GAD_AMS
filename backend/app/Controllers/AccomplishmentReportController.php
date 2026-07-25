<?php

namespace App\Controllers;

use App\Models\AccomplishmentReportModel;
use App\Libraries\FileStorage;
use CodeIgniter\API\ResponseTrait;

class AccomplishmentReportController extends BaseController
{
    use ResponseTrait;

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
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            throw new \Exception("Validation Failed: " . json_encode($errors));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Save uploaded PDFs to writable/uploads/drafts/
            $files = $this->request->getFileMultiple('attachments');
            $fileNames = [];
            if ($files) {
                foreach ($files as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileNames[] = FileStorage::saveToDrafts($file);
                    }
                }
            }

            $controlRecord = $db->table('activity_design')->select('act_design_id')->where('control_number', $this->request->getPost("control_number"))->get()->getRowArray();
            $actDesignId = $controlRecord ? $controlRecord['act_design_id'] : null;

            $data = [
                'activity_title'  => $this->request->getPost('activity_title'),
                'control_number'  => $this->request->getPost('control_number'),
                'act_design_id'   => $actDesignId,
                'start_date'      => $this->request->getPost('start_date'),
                'end_date'        => $this->request->getPost('end_date'),
                'start_time'      => $this->request->getPost('start_time'),
                'end_time'        => $this->request->getPost('end_time'),
                'venue'           => $this->request->getPost('venue'),
                'venue_id'        => $this->request->getPost('venue_id') ? (int)$this->request->getPost('venue_id') : null,
                'is_inside_bsu'   => filter_var($this->request->getPost('is_inside_bsu'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                'attendees'       => $this->request->getPost('attendees'),
                'male'            => $this->request->getPost('male'),
                'female'          => $this->request->getPost('female'),
                'rating'          => $this->request->getPost('rating'),
                'attachment'      => json_encode($fileNames),
                'user_id'         => $this->request->getPost('user_id'),
                'status'          => $this->request->getPost('status') ?: 'Pending'
            ];

            if (empty($data['user_id'])) {
                throw new \Exception("User ID is missing. Please log in again.");
            }

            // Validate that actual spending does not exceed proposed budget of the activity design
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

            $reportId = $accomplishmentReportModel->insert($data);

            if (!$reportId) {
                throw new \Exception("Failed to insert main report.");
            }

            // Update GAD alignment in archived_activity_designs / activity_design
            $classificationId = $this->request->getPost('classification_id') ?: $this->request->getPost('activity_classification_id');
            $gadMandateId = $this->request->getPost('gad_mandate_id');
            $genderIssueId = $this->request->getPost('gender_issue_id');

            if ($gadMandateId === 'Other' || $gadMandateId === 'new') {
                $customMandate = $this->request->getPost('custom_gad_mandate');
                if ($customMandate && $customMandate !== 'undefined') {
                    $db->table('gad_mandates')->insert([
                        'title' => $customMandate,
                        'code' => 'CUSTOM'
                    ]);
                    $gadMandateId = $db->insertID();
                }
            }

            if ($genderIssueId === 'Other' || $genderIssueId === 'new') {
                $customGenderIssue = $this->request->getPost('custom_gender_issue');
                if ($customGenderIssue && $customGenderIssue !== 'undefined') {
                    $db->table('gender_issues')->insert([
                        'title' => $customGenderIssue,
                        'mandate_id' => $gadMandateId
                    ]);
                    $genderIssueId = $db->insertID();
                }
            }

            $adUpdateData = [];
            if ($classificationId) {
                $adUpdateData['classification_id'] = $classificationId;
            }
            if ($this->request->getPost('form_type')) {
                $adUpdateData['form_type'] = $this->request->getPost('form_type');
            }

            $finalMandates = [];
            if ($gadMandateId) {
                $mandatesArr = explode(',', (string)$gadMandateId);
                foreach ($mandatesArr as $m) {
                    $finalMandates[] = trim($m);
                }
                if (!empty($finalMandates)) {
                    $adUpdateData['gad_mandate_id'] = $finalMandates[0];
                }
            }

            $finalIssues = [];
            if ($genderIssueId) {
                $issuesArr = explode(',', (string)$genderIssueId);
                foreach ($issuesArr as $i) {
                    $finalIssues[] = trim($i);
                }
                if (!empty($finalIssues)) {
                    $adUpdateData['gender_issue_id'] = $finalIssues[0];
                }
            }

            if (!empty($actDesignId)) {
                if (!empty($adUpdateData)) {
                    $db->table('archived_activity_designs')
                        ->where('original_act_design_id', $actDesignId)
                        ->update($adUpdateData);
                    
                    $db->table('activity_design')
                        ->where('act_design_id', $actDesignId)
                        ->update($adUpdateData);
                }

                // Update junction tables
                if (!empty($finalMandates)) {
                    $db->table('activity_design_mandates')->where('act_design_id', $actDesignId)->delete();
                    foreach ($finalMandates as $mId) {
                        $db->table('activity_design_mandates')->insert([
                            'act_design_id' => $actDesignId,
                            'mandate_id' => $mId
                        ]);
                    }
                }
                if (!empty($finalIssues)) {
                    $db->table('activity_design_issues')->where('act_design_id', $actDesignId)->delete();
                    foreach ($finalIssues as $iId) {
                        $db->table('activity_design_issues')->insert([
                            'act_design_id' => $actDesignId,
                            'issue_id' => $iId
                        ]);
                    }
                }
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
            $evalItemsJson = $this->request->getPost('evaluation_results') ?: $this->request->getPost('evaluation_items');
            if (!empty($evalItemsJson)) {
                $evalData = json_decode($evalItemsJson, true);
                if (is_array($evalData)) {
                    $inserts = [];
                    if (isset($evalData[0]) && is_array($evalData[0])) {
                        $evalMapping = [
                            'Time Management'                   => 'time_management',
                            'Orderliness and Program Flow'      => 'orderliness_and_program_flow',
                            'Appropriateness of the Venue'      => 'appropriateness_of_venue',
                            'Sound System and Hall Preparation' => 'sound_system_and_hall_preparation',
                            'Restroom/s'                        => 'restrooms',
                            'Food and Drinks'                   => 'food_and_drinks'
                        ];
                        foreach ($evalData as $item) {
                            $area = $item['area'] ?? '';
                            if (isset($evalMapping[$area])) {
                                $inserts[] = [
                                    'accomplishment_report_id' => $reportId,
                                    'question_key' => $evalMapping[$area],
                                    'score' => $item['rating'] ?: 0
                                ];
                            }
                        }
                    } else {
                        foreach ($evalData as $key => $score) {
                            $inserts[] = [
                                'accomplishment_report_id' => $reportId,
                                'question_key' => $key,
                                'score' => $score
                            ];
                        }
                    }
                    if (!empty($inserts)) {
                        $db->table('accomplishment_evaluation_results')->insertBatch($inserts);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    "success" => false,
                    "message" => "Transaction failed."
                ])->setStatusCode(500);
            }

            \App\Models\ActivityLogModel::log($data['user_id'], 'Submit Document', 'submitted Accomplishment Report: ' . $data['activity_title']);

            return $this->response->setJSON([
                "success" => true,
                "message" => "Data saved successfully"
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                "success" => false,
                "message" => "Server Error: " . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        $reports = $db->table('accomplishment_report as ar')
            ->select('ar.id, ar.status, ar.control_number as control, ar.activity_title as title, DATE(ar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, COALESCE(form_types.name, ad.form_type) as formLabel')
            ->join('users', 'users.id = ar.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('activity_design as ad', 'ad.control_number = ar.control_number', 'left')
            ->join('form_types', 'form_types.id = ad.form_type OR form_types.name = ad.form_type', 'left')
            ->where('ar.status !=', 'Verified')
            ->where('ar.deleted_at', null)
            ->where('ar.is_archived', 0)
            ->get()->getResultArray();

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

        $db = \Config\Database::connect();
        $reportModel = new AccomplishmentReportModel();
        
        $report = $reportModel
            ->select('
                accomplishment_report.*, 
                accomplishment_report.control_number as control,
                DATE(accomplishment_report.created_at) as date,
                office_units.office_name as office, 
                users.full_name as submitter_name, 
                users.email as email,
                archived_activity_designs.form_type,
                archived_activity_designs.form_type as formLabel,
                archived_activity_designs.classification_id,
                archived_activity_designs.gad_mandate_id,
                archived_activity_designs.gender_issue_id,
                gad_mandates.title as mandate_title, 
                gender_issues.title as gender_issue_title, 
                activity_classifications.classification_name
            ')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('archived_activity_designs', 'archived_activity_designs.original_act_design_id = accomplishment_report.act_design_id', 'left')
            ->join('gad_mandates', 'gad_mandates.id = archived_activity_designs.gad_mandate_id', 'left')
            ->join('gender_issues', 'gender_issues.id = archived_activity_designs.gender_issue_id', 'left')
            ->join('activity_classifications', 'activity_classifications.id = archived_activity_designs.classification_id', 'left')
            ->where('accomplishment_report.id', $id)
            ->first();

        if (!$report) {
            $report = $db->table('archived_accomplishment_reports as aar')
                ->select('
                    aar.*, 
                    aar.original_report_id as id, 
                    aar.activity_title as title, 
                    office_units.office_name as office, 
                    users.full_name as submitter_name, 
                    users.email as email,
                    aar.start_date as date, 
                    ad.form_type,
                    ad.form_type as formLabel,
                    ad.classification_id,
                    ad.gad_mandate_id,
                    ad.gender_issue_id,
                    gm.title as mandate_title, 
                    gi.title as gender_issue_title, 
                    ac.classification_name
                ')
                ->join('users', 'users.id = aar.user_id', 'left')
                ->join('office_units', 'office_units.office_id = users.office_id', 'left')
                ->join('archived_activity_designs as ad', 'ad.original_act_design_id = aar.act_design_id', 'left')
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


        if ($report) {
            $budgetModel = new \App\Models\AccomplishmentBudgetItemsModel();
            $budgetItems = $budgetModel->where('accomplishment_report_id', $id)->findAll();
            if ($budgetItems) {
                $budgetMap = [
                    'Meals' => 'meals_total',
                    'Snacks' => 'snacks_total',
                    'Function Room/Venue' => 'function_room_venue',
                    'Accommodation' => 'accommodation',
                    'Equipment Rental' => 'equipment_rental',
                    'Professional Fee/Honoria' => 'professional_fee_honoria',
                    'Professional Fee/Honoraria' => 'professional_fee_honoria',
                    'Token/s' => 'tokens',
                    'Materials and Supplies' => 'materials_and_supplies',
                    'Transportation' => 'transportation',
                    
                ];
                $flatBudget = [];
                foreach ($budgetItems as $item) {
                    $name = $item['item_name'];
                    if (isset($budgetMap[$name])) {
                        $flatBudget[$budgetMap[$name]] = $item['amount'];
                        if ($budgetMap[$name] === 'professional_fee_honoria') {
                            $flatBudget['pf_pax'] = $item['pax'];
                        }
                        if ($budgetMap[$name] === 'tokens') {
                            $flatBudget['tokens_pax'] = $item['pax'];
                        }
                        if ($name === 'Meals' && !empty($item['sub_item'])) {
                            $flatBudget['breakfast_selected'] = strpos(strtolower($item['sub_item']), 'breakfast') !== false ? 1 : 0;
                            $flatBudget['lunch_selected'] = strpos(strtolower($item['sub_item']), 'lunch') !== false ? 1 : 0;
                            $flatBudget['dinner_selected'] = strpos(strtolower($item['sub_item']), 'dinner') !== false ? 1 : 0;
                        }
                        if ($name === 'Snacks' && !empty($item['sub_item'])) {
                            $flatBudget['am_snack_selected'] = strpos(strtolower($item['sub_item']), 'am') !== false ? 1 : 0;
                            $flatBudget['pm_snack_selected'] = strpos(strtolower($item['sub_item']), 'pm') !== false ? 1 : 0;
                        }
                    } elseif ($name === 'Others') {
                        if (!isset($flatBudget['others_total'])) {
                            $flatBudget['others_total'] = 0;
                            $flatBudget['materials_others_breakdown'] = [];
                        }
                        $flatBudget['others_total'] += $item['amount'];
                        if (!empty($item['sub_item'])) {
                            $flatBudget['materials_others_breakdown'][] = [
                                'name' => $item['sub_item'],
                                'amount' => $item['amount']
                            ];
                        }
                    }
                }
                if (isset($flatBudget['materials_others_breakdown']) && is_array($flatBudget['materials_others_breakdown'])) {
                    $flatBudget['materials_others_breakdown'] = json_encode($flatBudget['materials_others_breakdown']);
                }
                $report['budget_items'] = [$flatBudget];
            } else {
                $report['budget_items'] = [];
            }
            


            $evalModel = new \App\Models\AccomplishmentEvaluationResultsModel();
            $evalRows = $evalModel->where('accomplishment_report_id', $id)->findAll();
            $flatEval = [];
            foreach ($evalRows as $row) {
                $flatEval[$row['question_key']] = $row['score'];
            }
            $report['evaluation_results'] = empty($flatEval) ? [] : [$flatEval];

            $controlNumber = $report['control'] ?? $report['control_number'] ?? null;
            if ($controlNumber) {
                $db = \Config\Database::connect();
                $ad = $db->table('activity_design as aad')
                      ->select('aad.*, venues.venue_name, activity_classifications.classification_name as activity_classification, form_types.name as form_type_name')
                      ->select('aad.start_date as date, office_units.office_name as office')
                      ->join('venues', 'venues.venue_id = aad.venue_id', 'left')
                      ->join('activity_classifications', 'activity_classifications.id = aad.classification_id', 'left')
                      ->join('form_types', 'form_types.id = aad.form_type', 'left')
                      ->join('users', 'users.id = aad.user_id', 'left')
                      ->join('office_units', 'office_units.office_id = users.office_id', 'left')
                      ->select('(SELECT GROUP_CONCAT(adm.mandate_id SEPARATOR \',\') FROM activity_design_mandates adm WHERE adm.act_design_id = aad.act_design_id) as gad_mandate_ids')
                        ->select('(SELECT GROUP_CONCAT(adi.issue_id SEPARATOR \',\') FROM activity_design_issues adi WHERE adi.act_design_id = aad.act_design_id) as gender_issue_ids')
                        ->where('aad.control_number', $controlNumber)
                        ->get()->getRowArray();
                      
                  if ($ad) {
                      $mandates = [];
                      if (!empty($ad['gad_mandate_ids'])) {
                            $mandateIds = array_map('trim', explode(',', $ad['gad_mandate_ids']));
                          $mandatesData = $db->table('gpb_items')->whereIn('id', $mandateIds)->get()->getResultArray();
                            foreach ($mandatesData as $m) {
                                  if (empty($m['mandate'])) {
                                      $mandates[] = 'GPB - N/A (Attributed Program) - ' . $m['activity'];
                                  } else {
                                      $mandates[] = 'GPB - ' . $m['mandate'];
                                  }
                              }
                      }
                      $ad['gad_mandate'] = implode(';;; ', $mandates);
                      $ad['gad_mandate_id'] = $ad['gad_mandate_ids'];
                      
                      $issues = [];
                      if (!empty($ad['gender_issue_ids'])) {
                            $issueIds = array_map('trim', explode(',', $ad['gender_issue_ids']));
                          $issuesData = $db->table('gpb_items')->whereIn('id', $issueIds)->get()->getResultArray();
                            foreach ($issuesData as $i) {
                                  if (empty($i['cause'])) {
                                      $issues[] = 'N/A (Attributed Program) - ' . $i['activity'];
                                  } else {
                                      $issues[] = $i['cause'];
                                  }
                              }
                      }
                      $ad['gender_issue'] = implode(';;; ', $issues);
                      $ad['gender_issue_id'] = $ad['gender_issue_ids'];
                  }
                
                if ($ad) {
                    $adBudgetModel = new \App\Models\ActivityBudgetItemsModel();
                    $adBudgetItems = $adBudgetModel->where('act_design_id', $ad['act_design_id'])->findAll();
                    if ($adBudgetItems) {
                        $adFlatBudget = [];
                        foreach ($adBudgetItems as $item) {
                            $name = $item['item_name'];
                            if (isset($budgetMap[$name])) {
                                $adFlatBudget[$budgetMap[$name]] = $item['amount'];
                                if ($budgetMap[$name] === 'professional_fee_honoria') {
                                    $adFlatBudget['pf_pax'] = $item['pax'];
                                }
                                if ($budgetMap[$name] === 'tokens') {
                                    $adFlatBudget['tokens_pax'] = $item['pax'];
                                }
                                if ($name === 'Meals' && !empty($item['sub_item'])) {
                                    $adFlatBudget['breakfast_selected'] = strpos(strtolower($item['sub_item']), 'breakfast') !== false ? 1 : 0;
                                    $adFlatBudget['lunch_selected'] = strpos(strtolower($item['sub_item']), 'lunch') !== false ? 1 : 0;
                                    $adFlatBudget['dinner_selected'] = strpos(strtolower($item['sub_item']), 'dinner') !== false ? 1 : 0;
                                }
                                if ($name === 'Snacks' && !empty($item['sub_item'])) {
                                    $adFlatBudget['am_snack_selected'] = strpos(strtolower($item['sub_item']), 'am') !== false ? 1 : 0;
                                    $adFlatBudget['pm_snack_selected'] = strpos(strtolower($item['sub_item']), 'pm') !== false ? 1 : 0;
                                }
                            } elseif ($name === 'Others') {
                                if (!isset($adFlatBudget['others_total'])) {
                                    $adFlatBudget['others_total'] = 0;
                                    $adFlatBudget['materials_others_breakdown'] = [];
                                }
                                $adFlatBudget['others_total'] += $item['amount'];
                                if (!empty($item['sub_item'])) {
                                    $adFlatBudget['materials_others_breakdown'][] = [
                                        'name' => $item['sub_item'],
                                        'amount' => $item['amount']
                                    ];
                                }
                            }
                        }
                        if (isset($adFlatBudget['materials_others_breakdown']) && is_array($adFlatBudget['materials_others_breakdown'])) {
                            $adFlatBudget['materials_others_breakdown'] = json_encode($adFlatBudget['materials_others_breakdown']);
                        }
                        $ad['budget_items'] = [$adFlatBudget];
                    } else {
                        $ad['budget_items'] = [];
                    }
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

        $reports = $db->table('accomplishment_report as ar')
            ->select('ar.id, ar.status, ar.control_number as control, ar.activity_title as title, DATE(ar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, COALESCE(form_types.name, ad.form_type) as formLabel')
            ->join('users', 'users.id = ar.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('activity_design as ad', 'ad.control_number = ar.control_number', 'left')
            ->join('form_types', 'form_types.id = ad.form_type OR form_types.name = ad.form_type', 'left')
            ->where('ar.user_id', $userId)
            ->where('ar.status !=', 'Verified')
            ->where('ar.deleted_at', null)
            ->where('ar.is_archived', 0)
            ->get()->getResultArray();

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
        $db = \Config\Database::connect();

        $reports = $db->table('accomplishment_report as ar')
            ->select('ar.*, ar.id, ar.control_number as control, ar.activity_title as title, DATE(ar.created_at) as date, office_units.office_name as office, users.full_name as submitter_name, ad.form_type as formLabel')
            ->join('users', 'users.id = ar.user_id', 'left')
            ->join('office_units', 'office_units.office_id = users.office_id', 'left')
            ->join('activity_design as ad', 'ad.control_number = ar.control_number', 'left')
            ->whereIn('ar.status', ['Verified', 'Cancelled'])
            ->where('ar.deleted_at', null)
            ->orderBy('ar.id', 'DESC')
            ->get()->getResultArray();

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
            'is_inside_bsu'  => $this->request->getPost('is_inside_bsu') !== null ? (filter_var($this->request->getPost('is_inside_bsu'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null,
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

        // Handle new file uploads (if any)
        $files = $this->request->getFileMultiple('attachments');
        if ($files && count($files) > 0 && $files[0]->isValid()) {
            // Clean up old files
            $oldAttachments = json_decode($report['attachment'], true);
            if (is_array($oldAttachments)) {
                foreach ($oldAttachments as $oldAtt) {
                    FileStorage::deleteFromDrafts($oldAtt);
                }
            } elseif (!empty($report['attachment'])) {
                FileStorage::deleteFromDrafts($report['attachment']);
            }
            
            $newNames = [];
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newNames[] = FileStorage::saveToDrafts($file);
                }
            }
            $updateData['attachment'] = json_encode($newNames);
        }

        try {
            if ($model->update($id, $updateData)) {
                
                // Update or Insert budget items
                $budgetItemsJson = $this->request->getPost('budget_items');
                if (!empty($budgetItemsJson)) {
                    $budgetData = json_decode($budgetItemsJson, true);
                    if (is_array($budgetData) && count($budgetData) > 0) {
                        $budgetModel = new \App\Models\AccomplishmentBudgetItemsModel();
                        $budgetModel->where('accomplishment_report_id', $id)->delete();
                        if (isset($budgetData[0])) {
                            foreach ($budgetData as &$item) {
                                $item['accomplishment_report_id'] = $id;
                            }
                            $budgetModel->insertBatch($budgetData);
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
                        $evalModel->where('accomplishment_report_id', $id)->delete();
                        $inserts = [];
                        foreach ($evalData as $key => $score) {
                            $inserts[] = [
                                'accomplishment_report_id' => $id,
                                'question_key' => $key,
                                'score' => $score
                            ];
                        }
                        if (!empty($inserts)) {
                            $evalModel->insertBatch($inserts);
                        }
                    }
                }

                // Update archived_activity_designs if fields are provided
                $adUpdateData = [];
                if ($this->request->getPost('activity_classification_id')) {
                    $adUpdateData['classification_id'] = $this->request->getPost('activity_classification_id');
                }
                if ($this->request->getPost('form_type')) {
                    $adUpdateData['form_type'] = $this->request->getPost('form_type');
                }
                
                $db = \Config\Database::connect();
                
                $customMandate = $this->request->getPost('custom_gad_mandate');
                  $mandateIdStr = $this->request->getPost('gad_mandate_id');
                  $finalMandates = [];
                  if ($mandateIdStr) {
                      $mandatesArr = explode(',', $mandateIdStr);
                      foreach ($mandatesArr as $m) {
                          if ($m === 'Other' || $m === 'new') {
                              if ($customMandate && $customMandate !== 'undefined') {
                                  $db->table('gad_mandates')->insert(['code' => 'CUSTOM', 'title' => $customMandate]);
                                  $finalMandates[] = $db->insertID();
                              }
                          } else {
                              $finalMandates[] = trim($m);
                          }
                      }
                  }
                  if (!empty($finalMandates)) {
                      $adUpdateData['gad_mandate_id'] = $finalMandates[0];
                  }
                  
                  $customIssue = $this->request->getPost('custom_gender_issue');
                  $issueIdStr = $this->request->getPost('gender_issue_id');
                  $finalIssues = [];
                  if ($issueIdStr) {
                      $issuesArr = explode(',', $issueIdStr);
                      foreach ($issuesArr as $i) {
                          if ($i === 'Other' || $i === 'new') {
                              if ($customIssue && $customIssue !== 'undefined') {
                                  $db->table('gender_issues')->insert([
                                      'mandate_id' => !empty($finalMandates) ? $finalMandates[0] : null,
                                      'title' => $customIssue
                                  ]);
                                  $finalIssues[] = $db->insertID();
                              }
                          } else {
                              $finalIssues[] = trim($i);
                          }
                      }
                  }
                  if (!empty($finalIssues)) {
                      $adUpdateData['gender_issue_id'] = $finalIssues[0];
                  }

                  // Junction table update logic will be injected below by another regex
                  if (!empty($adUpdateData) && !empty($report['control_number'])) {
                    $db = \Config\Database::connect();
                    $controlRecord = $db->table('activity_design')->select('act_design_id')->where('control_number', $report['control_number'])->get()->getRowArray();
                    if ($controlRecord && !empty($controlRecord['act_design_id'])) {
                        $db->table('activity_design')
                       ->where('act_design_id', $controlRecord['act_design_id'])
                       ->update($adUpdateData);
                      
                      // Update junction tables
                      $designIdToUpdate = $actDesignId ?? $controlRecord['act_design_id'] ?? null;
                      if ($designIdToUpdate) {
                          if (isset($finalMandates) && !empty($finalMandates)) {
                              $db->table('activity_design_mandates')->where('act_design_id', $designIdToUpdate)->delete();
                              foreach ($finalMandates as $mId) {
                                  $db->table('activity_design_mandates')->insert([
                                      'act_design_id' => $designIdToUpdate,
                                      'mandate_id' => $mId
                                  ]);
                              }
                          }
                          if (isset($finalIssues) && !empty($finalIssues)) {
                              $db->table('activity_design_issues')->where('act_design_id', $designIdToUpdate)->delete();
                              foreach ($finalIssues as $iId) {
                                  $db->table('activity_design_issues')->insert([
                                      'act_design_id' => $designIdToUpdate,
                                      'issue_id' => $iId
                                  ]);
                              }
                          }
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
        $db->table('accomplishment_report')->where('id', $id)->update([
            'status'      => 'Verified',
            'remarks'     => $remarks,
            'is_archived' => 1,
            'archived_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to verify and archive report'])->setStatusCode(500);
        }

        // Move PDF from drafts -> archived (outside transaction)
        $attachments = json_decode($item['attachment'], true);
        if (is_array($attachments)) {
            foreach ($attachments as $att) {
                FileStorage::moveToArchived($att);
            }
        } elseif (!empty($item['attachment'])) {
            FileStorage::moveToArchived($item['attachment']);
        }

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
        $remarks = $remarks ?: $this->request->getPost('remarks') ?: $this->request->getVar('remarks') ?: '';
        $aDate = $this->request->getPost('assessment_date') ?: $this->request->getVar('assessment_date') ?: date('Y-m-d');

        $updateData = [
            'status'          => 'Revision Required',
            'remarks'         => $remarks,
            'assessment_date' => $aDate
        ];
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

    public function markViewed($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        
        try {
            $updated = $db->table('accomplishment_report')->where('id', $id)->update(['is_viewed_by_admin' => 1]);
            return $this->response->setJSON(['success' => true, 'message' => 'Marked as viewed']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function unmarkViewed($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        
        try {
            $updated = $db->table('accomplishment_report')->where('id', $id)->update(['is_viewed_by_admin' => 0]);
            return $this->response->setJSON(['success' => true, 'message' => 'Unmarked as viewed']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function updateReport($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report ID required'])->setStatusCode(400);
        }

        $reportModel = new AccomplishmentReportModel();
        $report = $reportModel->find($id);

        if (!$report) {
            return $this->failNotFound('Report not found');
        }

        log_message('critical', 'RAW POST DATA: ' . json_encode($_POST));
        log_message('critical', 'RAW FILES: ' . json_encode($_FILES));

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
            $errors = $this->validator->getErrors();
            throw new \Exception("Validation Failed: " . json_encode($errors));
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
                'venue_id'       => $this->request->getPost('venue_id') ? (int)$this->request->getPost('venue_id') : null,
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

            // Update GAD alignment in archived_activity_designs
            $classificationId = $this->request->getPost('classification_id');
            $gadMandateId = $this->request->getPost('gad_mandate_id');
            $genderIssueId = $this->request->getPost('gender_issue_id');

            if ($gadMandateId === 'Other') {
                $customMandate = $this->request->getPost('custom_gad_mandate');
                $db->table('gad_mandates')->insert([
                    'title' => $customMandate,
                    'code' => 'CUSTOM'
                ]);
                $gadMandateId = $db->insertID();
            }

            if ($genderIssueId === 'Other') {
                $customGenderIssue = $this->request->getPost('custom_gender_issue');
                $db->table('gender_issues')->insert([
                    'title' => $customGenderIssue,
                    'mandate_id' => $gadMandateId
                ]);
                $genderIssueId = $db->insertID();
            }

            if (!empty($actDesignId)) {
                $db->table('archived_activity_designs')
                    ->where('original_act_design_id', $actDesignId)
                    ->update([
                        'classification_id' => $classificationId ? (int)$classificationId : null,
                        'gad_mandate_id'     => $gadMandateId ? (int)$gadMandateId : null,
                        'gender_issue_id'    => $genderIssueId ? (int)$genderIssueId : null,
                    ]);
            }

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

                foreach (evaluationItems as $item) {
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

        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $report['user_id'];
        
        $userModel = new \App\Models\UserModel();
        $actionUser = $userModel->find($actionUserId);
        $isAdmin = $actionUser && $actionUser['role'] === 'admin';
        $isOwner = ($actionUserId == $report['user_id']);

        if (!$isAdmin && !$isOwner) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized: You can only delete documents you submitted.'])->setStatusCode(403);
        }

        if (!$isAdmin && ($report['status'] !== 'Pending' || $report['is_viewed_by_admin'] == 1)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot trash this document as it is already being processed or viewed by admin.'])->setStatusCode(400);
        }

        $model->update($id, ['deleted_by' => $actionUserId]);

        if ($model->delete($id)) {
            \App\Models\ActivityLogModel::log($actionUserId, 'Trash Document', 'moved to trash Accomplishment Report: ' . $report['activity_title']);
            return $this->response->setJSON(['success' => true, 'message' => 'Accomplishment report moved to trash successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to move accomplishment report to trash'])->setStatusCode(500);
    }
}