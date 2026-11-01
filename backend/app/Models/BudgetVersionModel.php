<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetVersionModel extends Model
{
    protected $table = 'budget_versions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'fiscal_year', 'version_number', 'status', 'source',
        'organization_data', 'created_by', 'created_at', 'closed_at',
    ];
    protected $useTimestamps = false;
}
