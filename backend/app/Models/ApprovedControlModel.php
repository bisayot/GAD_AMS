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
     * for a given user.
     *
     * @param int $userId The ID of the user.
     * @return array An array of objects containing control number and activity design details.
     */
    public function getApprovedControlsWithActivityDetails(int $userId = null): array
    {
        $builder = $this->select('
                        control_number.control_number, 
                        control_number.act_design_id,
                        archived_activity_designs.*, 
                        COALESCE(venues.venue_name, archived_activity_designs.venue) as venue,
                        activity_classifications.classification_name as activity_classification,
                        gad_mandates.title as gad_mandate,
                        gender_issues.title as gender_issue
                    ')
                    ->join('archived_activity_designs', 'archived_activity_designs.original_act_design_id = control_number.act_design_id', 'inner')
                    ->join('accomplishment_report', 'accomplishment_report.control_number = control_number.control_number', 'left')
                    ->join('archived_accomplishment_reports', 'archived_accomplishment_reports.control_number = control_number.control_number', 'left')
                    ->join('venues', 'venues.venue_id = archived_activity_designs.venue_id', 'left')
                    ->join('activity_classifications', 'activity_classifications.id = archived_activity_designs.classification_id', 'left')
                    ->join('gad_mandates', 'gad_mandates.id = archived_activity_designs.gad_mandate_id', 'left')
                    ->join('gender_issues', 'gender_issues.id = archived_activity_designs.gender_issue_id', 'left')
                    ->where('archived_activity_designs.status', 'Approved')
                    ->where('accomplishment_report.id IS NULL')
                    ->where('archived_accomplishment_reports.archive_id IS NULL');

        if ($userId !== null) {
            $builder->where('control_number.user_id', $userId);
        }

        return $builder->findAll();
    }
}