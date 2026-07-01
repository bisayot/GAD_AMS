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
        return $this->select('
                        control_number.control_number, 
                        archived_activity_designs.*, 
                        venues.venue_name,
                        activity_classifications.classification_name as activity_classification,
                        form_types.name as form_type_name
                    ')
                    ->select('COALESCE((SELECT GROUP_CONCAT(gm.title SEPARATOR ", ") FROM archived_activity_design_mandates adm JOIN gad_mandates gm ON gm.id = adm.mandate_id WHERE adm.archive_id = archived_activity_designs.archive_id), (SELECT title FROM gad_mandates WHERE id = archived_activity_designs.gad_mandate_id)) as gad_mandate')
                    ->select('COALESCE((SELECT GROUP_CONCAT(gi.title SEPARATOR ", ") FROM archived_activity_design_issues adi JOIN gender_issues gi ON gi.id = adi.issue_id WHERE adi.archive_id = archived_activity_designs.archive_id), (SELECT title FROM gender_issues WHERE id = archived_activity_designs.gender_issue_id)) as gender_issue')
                    ->select('COALESCE((SELECT GROUP_CONCAT(adm.mandate_id SEPARATOR ",") FROM archived_activity_design_mandates adm WHERE adm.archive_id = archived_activity_designs.archive_id), archived_activity_designs.gad_mandate_id) as gad_mandate_ids')
                    ->select('COALESCE((SELECT GROUP_CONCAT(adi.issue_id SEPARATOR ",") FROM archived_activity_design_issues adi WHERE adi.archive_id = archived_activity_designs.archive_id), archived_activity_designs.gender_issue_id) as gender_issue_ids')
                    ->join('archived_activity_designs', 'archived_activity_designs.original_act_design_id = control_number.act_design_id')
                    ->join('accomplishment_report', 'accomplishment_report.control_number = control_number.control_number', 'left')
                    ->join('archived_accomplishment_reports', 'archived_accomplishment_reports.control_number = control_number.control_number', 'left')
                    ->join('venues', 'venues.venue_id = archived_activity_designs.venue_id', 'left')
                    ->join('activity_classifications', 'activity_classifications.id = archived_activity_designs.classification_id', 'left')
                    ->join('form_types', 'form_types.id = archived_activity_designs.form_type', 'left')
                    ->where('archived_activity_designs.user_id', $userId)
                    ->where('archived_activity_designs.status', 'Approved')
                    ->where('accomplishment_report.id IS NULL')
                    ->where('archived_accomplishment_reports.archive_id IS NULL')
                    ->findAll();
    }
}