<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovedControlModel extends Model
{
    protected $table = 'control_number'; // Primary table for this model
    protected $primaryKey = 'control_number_id';
    protected $allowedFields = ['control_number', 'act_design_id', 'user_id'];

    /**
     * Fetches approved control numbers along with their associated activity design details
     * for a given user. Excludes control numbers that already have an accomplishment report
     * (whether pending/active or archived/completed).
     *
     * @param int $userId The ID of the user.
     * @return array An array of objects containing control number and activity design details.
     */
    public function getApprovedControlsWithActivityDetails(int $userId): array
    {
        return $this->select('control_number.control_number, archived_activity_designs.*, venues.venue_name')
                    ->join('archived_activity_designs', 'archived_activity_designs.original_act_design_id = control_number.act_design_id')
                    ->join('accomplishment_report', 'accomplishment_report.control_number = control_number.control_number', 'left')
                    ->join('archived_accomplishment_reports', 'archived_accomplishment_reports.control_number = control_number.control_number', 'left')
                    ->join('venues', 'venues.venue_id = archived_activity_designs.venue_id', 'left')
                    ->where('archived_activity_designs.user_id', $userId)
                    ->where('archived_activity_designs.status', 'Approved')
                    ->where('accomplishment_report.id IS NULL')
                    ->where('archived_accomplishment_reports.archive_id IS NULL')
                    ->findAll();
    }
}