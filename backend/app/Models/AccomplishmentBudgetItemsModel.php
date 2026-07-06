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
        'transportation',
        'meals_total',
        'snacks_total',
        'breakfast_selected',
        'lunch_selected',
        'dinner_selected',
        'am_snack_selected',
        'pm_snack_selected',
        'others_total',
        'meals_total',
        'snacks_total',
        'breakfast_selected',
        'lunch_selected',
        'dinner_selected',
        'am_snack_selected',
        'pm_snack_selected',
        'others_total',
        'materials_others_breakdown'
    ];
}
