<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityBudgetItemsModel extends Model
{
    protected $table = 'activity_budget_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'act_design_id',
        'category_id',
        'item_name',
        'sub_item',
        'pax',
        'amount'
    ];
}
