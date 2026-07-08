<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovedControlModel extends Model
{
    protected $table = 'activity_design'; // Primary table for this model
    protected $primaryKey = 'act_design_id';
    protected $allowedFields = ['control_number', 'user_id'];

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
        return $this->select('
                        activity_design.*, 
                        venues.venue_name,
                        activity_classifications.classification_name as activity_classification,
                        form_types.name as form_type_name
                    ')
                    
                    ->join('accomplishment_report', 'accomplishment_report.control_number = activity_design.control_number', 'left')
                    ->join('venues', 'venues.venue_id = activity_design.venue_id', 'left')
                    ->join('activity_classifications', 'activity_classifications.id = activity_design.classification_id', 'left')
                    ->join('form_types', 'form_types.id = activity_design.form_type', 'left')
                    ->where('activity_design.user_id', $userId)
                    ->where('activity_design.status', 'Approved')
                    ->where('activity_design.is_archived', 1)
                    ->where('accomplishment_report.id IS NULL')
                    ->findAll();
    }
}