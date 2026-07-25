<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetCategoryModel extends Model
{
    protected $table = 'budget_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name'
    ];
}
