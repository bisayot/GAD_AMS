<?php

namespace App\Models;

use CodeIgniter\Model;

class AccomplishmentBudgetItemsModel extends Model
{
    protected $table = 'accomplishment_budget_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'accomplishment_report_id',
        'category_id',
        'item_name',
        'sub_item',
        'pax',
        'amount'
    ];
}
