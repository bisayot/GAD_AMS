<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ArchiveController extends ResourceController
{
    protected $format = 'json';

    /**
     * Fetch all records from the archive table
     */
    public function index()
    {
        $db = \Config\Database::connect();
        $archives = $db->table('archives')
            ->orderBy('archived_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->respond([
            'success' => true,
            'data'    => $archives
        ]);
    }

    /**
     * Move an Activity Design to Archive (Physical move)
     */
    public function archiveDesign($id = null)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Fetch original data with joins for metadata
        $item = $db->table('activity_design')
            ->select('activity_design.*, control_number.control_number as control, users.username as office')
            ->join('users', 'users.id = activity_design.user_id', 'left')
            ->join('control_number', 'control_number.act_design_id = activity_design.act_design_id', 'left')
            ->where('activity_design.act_design_id', $id)
            ->get()->getRowArray();

        if (!$item) return $this->failNotFound('Design not found');

        // 2. Prepare Archive record
        $archiveData = [
            'original_id' => $item['act_design_id'],
            'type'        => 'design',
            'control'     => $item['control'],
            'title'       => $item['activity_title'],
            'office'      => $item['office'],
            'form_label'  => $item['form_type'],
            'status'      => 'Approved',
            'item_date'   => $item['start_date'],
            'user_id'     => $item['user_id'],
            'raw_data'    => json_encode($item) // Snapshot for ADView
        ];

        // 3. Insert and Delete
        $db->table('archives')->insert($archiveData);
        $db->table('activity_design')->where('act_design_id', $id)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Failed to move design to archive');
        }

        return $this->respond(['success' => true, 'message' => 'Activity Design archived and cleared from active list']);
    }

    /**
     * Move an Accomplishment Report to Archive (Physical move)
     */
    public function archiveReport($id = null)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $item = $db->table('accomplishment_report')
            ->select('accomplishment_report.*, control_number.control_number as control, users.username as office, activity_design.form_type as formLabel')
            ->join('users', 'users.id = accomplishment_report.user_id', 'left')
            ->join('control_number', 'control_number.control_number = accomplishment_report.control_number', 'left')
            ->join('activity_design', 'activity_design.act_design_id = control_number.act_design_id', 'left')
            ->where('accomplishment_report.id', $id)
            ->get()->getRowArray();

        if (!$item) return $this->failNotFound('Report not found');

        $archiveData = [
            'original_id' => $item['id'],
            'type'        => 'report',
            'control'     => $item['control'],
            'title'       => $item['activity_title'],
            'office'      => $item['office'],
            'form_label'  => $item['formLabel'],
            'status'      => 'Verified',
            'item_date'   => $item['start_date'],
            'user_id'     => $item['user_id'],
            'raw_data'    => json_encode($item) // Snapshot for ARView
        ];

        $db->table('archives')->insert($archiveData);
        $db->table('accomplishment_report')->where('id', $id)->delete();

        $db->transComplete();
        return $this->respond(['success' => true, 'message' => 'Accomplishment Report archived and cleared']);
    }
}