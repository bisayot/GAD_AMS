<?php

namespace App\Models;

use CodeIgniter\Model;

class AccomplishmentBudgetItemsModel extends Model
{
    protected $table = 'accomplishment_budget_items';
    protected $primaryKey = 'item_id';
    protected $allowedFields = [
        'accomplishment_report_id',
        'meals_and_snacks',
        'function_room_venue',
        'accommodation',
        'equipment_rental',
        'professional_fee_honoria',
        'tokens',
        'materials_and_supplies',
        'transportation'
    ];
}
