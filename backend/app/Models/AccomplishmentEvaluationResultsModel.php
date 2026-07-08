<?php

namespace App\Models;

use CodeIgniter\Model;

class AccomplishmentEvaluationResultsModel extends Model
{
    protected $table = 'evaluation_results';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'accomplishment_report_id',
        'question_key',
        'score'
    ];
}
