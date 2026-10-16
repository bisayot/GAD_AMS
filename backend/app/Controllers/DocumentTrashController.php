<?php

namespace App\Controllers;

use App\Models\ActivityDesignModel;
use App\Models\AccomplishmentReportModel;
use App\Libraries\FileStorage;

class DocumentTrashController extends BaseController
{
    public function getTrashedDocuments()
    {
        $db = \Config\Database::connect();
        $actionUserId = $this->request->getHeaderLine('X-User-Id');
        $actionUser = (new \App\Models\UserModel())->find($actionUserId);
        $actionRole = strtolower($actionUser['role'] ?? '');
        $isAdmin = $actionRole === 'admin';
        $isStaff = $actionRole === 'gad_staff';
        
        // --- 30-Day Auto Cleanup Logic ---
        $this->autoCleanup($db, 'activity_design');
        $this->autoCleanup($db, 'accomplishment_report');

        // Fetch Trashed Activity Designs
        $adModel = new ActivityDesignModel();
        $adQuery = $adModel->onlyDeleted()
            ->select('activity_design.act_design_id as id, activity_design.activity_title as title, activity_design.form_type as type, users.full_name as submitter_name, deleter.full_name as deleted_by_name, activity_design.deleted_at')
            ->join('users', 'users.id = activity_design.user_id', 'left')
            ->join('users as deleter', 'deleter.id = activity_design.deleted_by', 'left');
            
        if ($isStaff) {
            $adQuery->groupStart()
                ->whereIn('users.role', ['twg', 'non-twg'])
                ->orWhere('activity_design.user_id', $actionUserId)
                ->groupEnd();
        } elseif (!$isAdmin) {
            $adQuery->where('activity_design.user_id', $actionUserId);
        }
        $trashedDesigns = $adQuery->findAll();

        foreach ($trashedDesigns as &$td) {
            $td['doc_type'] = 'design';
            $date = new \DateTime($td['deleted_at']);
            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
            $td['deleted_date'] = $date->format('M d, Y h:i A');
        }

        // Fetch Trashed Accomplishment Reports
        $arModel = new AccomplishmentReportModel();
        $arQuery = $arModel->onlyDeleted()
            ->select('accomplishment_report.id, accomplishment_report.activity_title as title, users.full_name as submitter_name, deleter.full_name as deleted_by_name, accomplishment_report.deleted_at')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('users as deleter', 'deleter.id = accomplishment_report.deleted_by', 'left');
            
        if ($isStaff) {
            $arQuery->groupStart()
                ->whereIn('users.role', ['twg', 'non-twg'])
                ->orWhere('accomplishment_report.user_id', $actionUserId)
                ->groupEnd();
        } elseif (!$isAdmin) {
            $arQuery->where('accomplishment_report.user_id', $actionUserId);
        }
        $trashedReports = $arQuery->findAll();

        foreach ($trashedReports as &$tr) {
            $tr['type'] = 'Accomplishment Report';
            $tr['doc_type'] = 'report';
            $date = new \DateTime($tr['deleted_at']);
            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
            $tr['deleted_date'] = $date->format('M d, Y h:i A');
        }

        $allTrashed = array_merge($trashedDesigns, $trashedReports);
        
        // Sort by deleted_at descending
        usort($allTrashed, function($a, $b) {
            return strtotime($b['deleted_at']) - strtotime($a['deleted_at']);
        });

        return $this->response->setJSON([
            'success' => true,
            'data' => $allTrashed
        ]);
    }

    private function autoCleanup($db, $table)
    {
        $idColumn = $table === 'activity_design' ? 'act_design_id' : 'id';
        
        // Find documents deleted more than 30 days ago
        $oldRecords = $db->table($table)
            ->where('deleted_at IS NOT NULL')
            ->where('deleted_at <', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->get()->getResultArray();

        $idsToDelete = [];
        foreach ($oldRecords as $record) {
            $idsToDelete[] = $record[$idColumn];
            if (!empty($record['attachment'])) {
                FileStorage::deleteFromDrafts($record['attachment']);
            }
        }

        if (!empty($idsToDelete)) {
            if ($table === 'activity_design') {
                $db->table('activity_budget_items')->whereIn('act_design_id', $idsToDelete)->delete();
                $db->table('activity_design_mandates')->whereIn('act_design_id', $idsToDelete)->delete();
                $db->table('activity_design_issues')->whereIn('act_design_id', $idsToDelete)->delete();
                $db->table('activity_schedules')->whereIn('act_design_id', $idsToDelete)->delete();
            } else if ($table === 'accomplishment_report') {
                $db->table('accomplishment_budget_items')->whereIn('accomplishment_report_id', $idsToDelete)->delete();
                $db->table('accomplishment_evaluation_results')->whereIn('accomplishment_report_id', $idsToDelete)->delete();
                $db->table('accomplishment_schedules')->whereIn('accomplishment_report_id', $idsToDelete)->delete();
            }

            // Hard delete them
            $db->table($table)->whereIn($idColumn, $idsToDelete)->delete();
        }
    }

    public function permanentlyDelete()
    {
        $json = $this->request->getJSON();
        $items = $json->items ?? [];
        if (empty($items)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No items provided'])->setStatusCode(400);
        }

        $adModel = new ActivityDesignModel();
        $arModel = new AccomplishmentReportModel();

        $designIds = [];
        $reportIds = [];

        foreach ($items as $item) {
            if ($item->doc_type === 'design') {
                $designIds[] = $item->id;
            } elseif ($item->doc_type === 'report') {
                $reportIds[] = $item->id;
            }
        }
        
        $actionUserId = $this->request->getHeaderLine('X-User-Id');
        $userModel = new \App\Models\UserModel();
        $actionUser = $userModel->find($actionUserId);
        $actionRole = strtolower($actionUser['role'] ?? '');
        $isAdmin = $actionRole === 'admin';
        $isStaff = $actionRole === 'gad_staff';

        if (!empty($designIds)) {
            $query = $adModel->onlyDeleted()->whereIn('act_design_id', $designIds);
            if ($isStaff) {
                $query->join('users as owner', 'owner.id = activity_design.user_id', 'inner')
                    ->groupStart()
                    ->whereIn('owner.role', ['twg', 'non-twg'])
                    ->orWhere('activity_design.user_id', $actionUserId)
                    ->groupEnd();
            } elseif (!$isAdmin) {
                $query->where('user_id', $actionUserId);
            }
            $records = $query->findAll();
            $validDesignIds = array_column($records, 'act_design_id');
            foreach ($records as $record) {
                if (!empty($record['attachment'])) {
                    FileStorage::deleteFromDrafts($record['attachment']);
                }
            }
            if (!empty($validDesignIds)) {
                $db = \Config\Database::connect();
                $db->table('activity_budget_items')->whereIn('act_design_id', $validDesignIds)->delete();
                $db->table('activity_design_mandates')->whereIn('act_design_id', $validDesignIds)->delete();
                $db->table('activity_design_issues')->whereIn('act_design_id', $validDesignIds)->delete();
                $db->table('activity_schedules')->whereIn('act_design_id', $validDesignIds)->delete();
                
                $adModel->delete($validDesignIds, true); // true = purge
            }
        }

        if (!empty($reportIds)) {
            $query = $arModel->onlyDeleted()->whereIn('id', $reportIds);
            if ($isStaff) {
                $query->join('users as owner', 'owner.id = accomplishment_report.user_id', 'inner')
                    ->groupStart()
                    ->whereIn('owner.role', ['twg', 'non-twg'])
                    ->orWhere('accomplishment_report.user_id', $actionUserId)
                    ->groupEnd();
            } elseif (!$isAdmin) {
                $query->where('user_id', $actionUserId);
            }
            $records = $query->findAll();
            $validReportIds = array_column($records, 'id');
            foreach ($records as $record) {
                if (!empty($record['attachment'])) {
                    FileStorage::deleteFromDrafts($record['attachment']);
                }
            }
            if (!empty($validReportIds)) {
                $db = \Config\Database::connect();
                $db->table('accomplishment_budget_items')->whereIn('accomplishment_report_id', $validReportIds)->delete();
                $db->table('accomplishment_evaluation_results')->whereIn('accomplishment_report_id', $validReportIds)->delete();
                $db->table('accomplishment_schedules')->whereIn('accomplishment_report_id', $validReportIds)->delete();
                
                $arModel->delete($validReportIds, true);
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Permanently deleted selected items']);
    }

    public function restore()
    {
        $json = $this->request->getJSON();
        $items = $json->items ?? [];
        if (empty($items)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No items provided'])->setStatusCode(400);
        }

        $adModel = new ActivityDesignModel();
        $arModel = new AccomplishmentReportModel();

        $designIds = [];
        $reportIds = [];

        foreach ($items as $item) {
            if ($item->doc_type === 'design') {
                $designIds[] = $item->id;
            } elseif ($item->doc_type === 'report') {
                $reportIds[] = $item->id;
            }
        }

        $actionUserId = $this->request->getHeaderLine('X-User-Id');
        $userModel = new \App\Models\UserModel();
        $actionUser = $userModel->find($actionUserId);
        $actionRole = strtolower($actionUser['role'] ?? '');
        $isAdmin = $actionRole === 'admin';
        $isStaff = $actionRole === 'gad_staff';
        $db = \Config\Database::connect();

        // --- Conflict detection: find trashed items that already have an active duplicate ---
        $conflicts = [];

        if (!empty($designIds)) {
            $trashedDesignQuery = $adModel->onlyDeleted()->whereIn('act_design_id', $designIds);
            if ($isStaff) {
                $trashedDesignQuery->join('users as owner', 'owner.id = activity_design.user_id', 'inner')
                    ->groupStart()
                    ->whereIn('owner.role', ['twg', 'non-twg'])
                    ->orWhere('activity_design.user_id', $actionUserId)
                    ->groupEnd();
            } elseif (!$isAdmin) {
                $trashedDesignQuery->where('activity_design.user_id', $actionUserId);
            }
            $trashedDesigns = $trashedDesignQuery->findAll();
            foreach ($trashedDesigns as $td) {
                // Look for a live (non-deleted) record with same activity_title + user_id
                $duplicate = $db->table('activity_design')
                    ->where('activity_title', $td['activity_title'])
                    ->where('user_id', $td['user_id'])
                    ->where('deleted_at IS NULL', null, false)
                    ->where('act_design_id !=', $td['act_design_id'])
                    ->get()->getRowArray();
                if ($duplicate) {
                    $conflicts[] = [
                        'doc_type' => 'design',
                        'id'       => $td['act_design_id'],
                        'title'    => $td['activity_title'],
                        'message'  => 'An active Activity Design with the same title already exists.',
                    ];
                }
            }
        }

        if (!empty($reportIds)) {
            $trashedReportQuery = $arModel->onlyDeleted()->whereIn('id', $reportIds);
            if ($isStaff) {
                $trashedReportQuery->join('users as owner', 'owner.id = accomplishment_report.user_id', 'inner')
                    ->groupStart()
                    ->whereIn('owner.role', ['twg', 'non-twg'])
                    ->orWhere('accomplishment_report.user_id', $actionUserId)
                    ->groupEnd();
            } elseif (!$isAdmin) {
                $trashedReportQuery->where('accomplishment_report.user_id', $actionUserId);
            }
            $trashedReports = $trashedReportQuery->findAll();
            foreach ($trashedReports as $tr) {
                // Look for a live (non-deleted) record with the same control_number
                $duplicate = $db->table('accomplishment_report')
                    ->where('control_number', $tr['control_number'])
                    ->where('deleted_at IS NULL', null, false)
                    ->where('id !=', $tr['id'])
                    ->get()->getRowArray();
                if ($duplicate) {
                    $conflicts[] = [
                        'doc_type' => 'report',
                        'id'       => $tr['id'],
                        'title'    => $tr['activity_title'],
                        'message'  => 'An active Accomplishment Report with control number "' . $tr['control_number'] . '" already exists.',
                    ];
                }
            }
        }

        // Abort entirely if any conflicts found
        if (!empty($conflicts)) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Restore cancelled: one or more items conflict with an existing active document.',
                'conflicts' => $conflicts,
            ])->setStatusCode(409);
        }

        // No conflicts — proceed with restore
        if (!empty($designIds)) {
            $builder = $adModel->builder()->whereIn('act_design_id', $designIds);
            if ($isStaff) {
                $builder->join('users as owner', 'owner.id = activity_design.user_id', 'inner')
                    ->groupStart()
                    ->whereIn('owner.role', ['twg', 'non-twg'])
                    ->orWhere('activity_design.user_id', $actionUserId)
                    ->groupEnd();
            } elseif (!$isAdmin) {
                $builder->where('user_id', $actionUserId);
            }
            $builder->update(['deleted_at' => null, 'deleted_by' => null]);
        }
        
        if (!empty($reportIds)) {
            $builder = $arModel->builder()->whereIn('id', $reportIds);
            if ($isStaff) {
                $builder->join('users as owner', 'owner.id = accomplishment_report.user_id', 'inner')
                    ->groupStart()
                    ->whereIn('owner.role', ['twg', 'non-twg'])
                    ->orWhere('accomplishment_report.user_id', $actionUserId)
                    ->groupEnd();
            } elseif (!$isAdmin) {
                $builder->where('user_id', $actionUserId);
            }
            $builder->update(['deleted_at' => null, 'deleted_by' => null]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Restored selected items']);
    }
}
