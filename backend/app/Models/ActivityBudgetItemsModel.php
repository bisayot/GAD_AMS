<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityBudgetItemsModel extends Model
{
    protected $table = 'activity_budget_items';
    protected $primaryKey = 'item_id';
    protected $allowedFields = [
        'act_design_id',
        'meals_and_snacks',
        'function_room_venue',
        'accommodation',
        'equipment_rental',
        'professional_fee_honoria',
        'tokens',
        'materials_and_supplies',
        'transportation',
        'meals_total',
        'snacks_total',
        'materials_total',
        'others_total',
        'breakfast_selected',
        'lunch_selected',
        'dinner_selected',
        'am_snack_selected',
        'pm_snack_selected',
        'materials_others_breakdown'
    ];
}
