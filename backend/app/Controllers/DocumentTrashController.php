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
        $userId = $this->request->getGet('user_id');
        
        // --- 30-Day Auto Cleanup Logic ---
        $this->autoCleanup($db, 'activity_design');
        $this->autoCleanup($db, 'accomplishment_report');

        // Fetch Trashed Activity Designs
        $adModel = new ActivityDesignModel();
        $adQuery = $adModel->onlyDeleted()
            ->select('activity_design.act_design_id as id, activity_design.activity_title as title, activity_design.form_type as type, users.full_name as submitter_name, deleter.full_name as deleted_by_name, activity_design.deleted_at')
            ->join('users', 'users.id = activity_design.user_id', 'left')
            ->join('users as deleter', 'deleter.id = activity_design.deleted_by', 'left');
            
        if ($userId) {
            $adQuery->where('activity_design.deleted_by', $userId);
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
            
        if ($userId) {
            $arQuery->where('accomplishment_report.deleted_by', $userId);
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
        // Find documents deleted more than 30 days ago
        $oldRecords = $db->table($table)
            ->where('deleted_at IS NOT NULL')
            ->where('deleted_at <', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->get()->getResultArray();

        foreach ($oldRecords as $record) {
            if (!empty($record['attachment'])) {
                FileStorage::deleteFromDrafts($record['attachment']);
            }
        }

        // Hard delete them
        $db->table($table)
            ->where('deleted_at IS NOT NULL')
            ->where('deleted_at <', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->delete();
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

        if (!empty($designIds)) {
            $records = $adModel->onlyDeleted()->whereIn('act_design_id', $designIds)->findAll();
            foreach ($records as $record) {
                if (!empty($record['attachment'])) {
                    FileStorage::deleteFromDrafts($record['attachment']);
                }
            }
            $adModel->delete($designIds, true); // true = purge
        }

        if (!empty($reportIds)) {
            $records = $arModel->onlyDeleted()->whereIn('id', $reportIds)->findAll();
            foreach ($records as $record) {
                if (!empty($record['attachment'])) {
                    FileStorage::deleteFromDrafts($record['attachment']);
                }
            }
            $arModel->delete($reportIds, true);
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

        if (!empty($designIds)) {
            // To restore, we set deleted_at back to null
            $adModel->builder()->whereIn('act_design_id', $designIds)->update(['deleted_at' => null, 'deleted_by' => null]);
        }
        
        if (!empty($reportIds)) {
            $arModel->builder()->whereIn('id', $reportIds)->update(['deleted_at' => null, 'deleted_by' => null]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Restored selected items']);
    }
}
