<?php

namespace App\Models;

use CodeIgniter\Model;

class AccomplishmentEvaluationResultsModel extends Model
{
    protected $table = 'accomplishment_evaluation_results';
    protected $primaryKey = 'evaluation_id';
    protected $allowedFields = [
        'accomplishment_report_id',
        'time_management',
        'orderliness_and_program_flow',
        'appropriateness_of_venue',
        'sound_system_and_hall_preparation',
        'restrooms',
        'food_and_drinks'
    ];
}
