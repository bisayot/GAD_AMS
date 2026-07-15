<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class ArchiveController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $db = \Config\Database::connect();
        $authHeader = $this->request->getHeaderLine('Authorization');
        $userId = $this->request->getGet('user_id');
        $role = $this->request->getGet('role');

        $designsQuery = $db->table('activity_design as aad')
            ->select('aad.*, aad.act_design_id as original_id, "design" as type, aad.activity_title as title, aad.form_type as form_label, users.username as office, aad.start_date as date, COALESCE(aad.archived_at, aad.end_date) as dateRaw')
            ->join('users', 'users.id = aad.user_id', 'left')
            ->select('COALESCE(aad.control_number, "N/A") as control')
            ->where('aad.is_archived', 1)
            ->where('aad.deleted_at IS NULL', null, false);

        $reportsQuery = $db->table('accomplishment_report as aar')
            ->select('aar.*, aar.id as original_id, "report" as type, aar.activity_title as title, "N/A" as form_label, users.username as office, aar.start_date as date, aar.control_number as control, COALESCE(aar.archived_at, aar.start_date) as dateRaw')
            ->join('users', 'users.id = aar.user_id', 'left')
            ->where('aar.is_archived', 1)
            ->where('aar.deleted_at IS NULL', null, false);

        if ($role && $role !== 'admin' && $role !== 'gad_staff') {
            $designsQuery->where('aad.user_id', $userId);
            $reportsQuery->where('aar.user_id', $userId);
        }

        $designs = $designsQuery->get()->getResultArray();
        $reports = $reportsQuery->get()->getResultArray();

        $allArchives = array_merge($designs, $reports);

        usort($allArchives, function ($a, $b) {
            return strtotime($b['dateRaw']) <=> strtotime($a['dateRaw']);
        });

        return $this->respond([
            'success' => true,
            'data'    => $allArchives
        ]);
    }

    public function archiveDesign($id)
    {
        $db = \Config\Database::connect();
        
        // Ensure only Approved or Cancelled are archived
        $design = $db->table('activity_design')->where('act_design_id', $id)->get()->getRowArray();
        if (!$design) {
            return $this->failNotFound('Design not found');
        }

        if ($design['status'] !== 'Approved' && $design['status'] !== 'Cancelled') {
            return $this->fail('Only Approved or Cancelled designs can be archived.');
        }

        if ($design['is_archived']) {
            return $this->respond(['success' => true, 'message' => 'Design already archived']);
        }

        $db->table('activity_design')->where('act_design_id', $id)->update(['is_archived' => 1]);
        
        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $design['user_id'];
        \App\Models\ActivityLogModel::log($actionUserId, 'Archive Document', 'archived Activity Design: ' . $design['activity_title']);
        return $this->respond(['success' => true, 'message' => 'Design successfully archived.']);
    }

    public function archiveReport($id)
    {
        $db = \Config\Database::connect();
        
        $report = $db->table('accomplishment_report')->where('id', $id)->get()->getRowArray();
        if (!$report) {
            return $this->failNotFound('Report not found');
        }

        if ($report['status'] !== 'Verified' && $report['status'] !== 'Cancelled') {
            return $this->fail('Only Verified or Cancelled reports can be archived.');
        }

        if ($report['is_archived']) {
            return $this->respond(['success' => true, 'message' => 'Report already archived']);
        }

        $db->table('accomplishment_report')->where('id', $id)->update(['is_archived' => 1]);
        
        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $report['user_id'];
        \App\Models\ActivityLogModel::log($actionUserId, 'Archive Document', 'archived Accomplishment Report: ' . $report['activity_title']);
        return $this->respond(['success' => true, 'message' => 'Report successfully archived.']);
    }
}