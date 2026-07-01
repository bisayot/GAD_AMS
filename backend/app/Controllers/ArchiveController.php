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
        
        if ($authHeader && !$userId) {
            $token = str_replace('Bearer ', '', $authHeader);
            // Since we don't have a token column, this is disabled for now
            // $user = $db->table('users')->where('token', $token)->get()->getRow();
            // if ($user) {
            //     $userId = $user->id;
            //     $role = $user->role;
            // }
        }

        $designsQuery = $db->table('archived_activity_designs as aad')
            ->select('aad.*, aad.original_act_design_id as original_id, "design" as type, aad.activity_title as title, aad.form_type as form_label, users.username as office, aad.start_date as date, aad.archived_at as dateRaw')
            ->join('users', 'users.id = aad.user_id', 'left')
            ->join('control_number as cn', 'cn.act_design_id = aad.original_act_design_id', 'left')
            ->select('COALESCE(cn.control_number, "N/A") as control');

        $reportsQuery = $db->table('archived_accomplishment_reports as aar')
            ->select('aar.*, aar.original_report_id as original_id, "report" as type, aar.activity_title as title, "N/A" as form_label, users.username as office, aar.start_date as date, aar.control_number as control, aar.archived_at as dateRaw')
            ->join('users', 'users.id = aar.user_id', 'left');

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

        // Check if already archived
        $existing = $db->table('archived_activity_designs')->where('original_act_design_id', $id)->get()->getRowArray();
        if ($existing) {
            return $this->respond(['success' => true, 'message' => 'Design already archived']);
        }

        // Build archive data
        $archiveData = $design;
        $archiveData['original_act_design_id'] = $archiveData['act_design_id'];
        unset($archiveData['act_design_id']); // Let auto-increment handle new ID

        $db->table('archived_activity_designs')->insert($archiveData);
        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $design['user_id'];
        \App\Models\ActivityLogModel::log($actionUserId, 'Cancel Document', 'cancelled Activity Design: ' . $design['activity_title']);
        return $this->respond(['success' => true, 'message' => 'Design successfully archived.']);
    }

    public function archiveReport($id)
    {
        $db = \Config\Database::connect();
        
        $report = $db->table('accomplishment_report')->where('report_id', $id)->get()->getRowArray();
        if (!$report) {
            return $this->failNotFound('Report not found');
        }

        if ($report['status'] !== 'Verified' && $report['status'] !== 'Cancelled') {
            return $this->fail('Only Verified or Cancelled reports can be archived.');
        }

        $existing = $db->table('archived_accomplishment_reports')->where('original_report_id', $id)->get()->getRowArray();
        if ($existing) {
            return $this->respond(['success' => true, 'message' => 'Report already archived']);
        }

        // Build archive data
        $archiveData = $report;
        $archiveData['original_report_id'] = $archiveData['report_id'] ?? $archiveData['id'];
        unset($archiveData['report_id']);
        unset($archiveData['id']);

        $db->table('archived_accomplishment_reports')->insert($archiveData);
        $actionUserId = $this->request->getHeaderLine('X-User-Id') ?: $report['user_id'];
        \App\Models\ActivityLogModel::log($actionUserId, 'Cancel Document', 'cancelled Accomplishment Report: ' . $report['activity_title']);
        return $this->respond(['success' => true, 'message' => 'Report successfully archived.']);
    }
}