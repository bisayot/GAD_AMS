<?php

namespace App\Controllers;

use App\Models\ApprovedControlModel;
use App\Models\ActivityBudgetItemsModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class ApprovedControlsController extends Controller
{
    use ResponseTrait;

    /**
     * Get approved control numbers and their associated activity design details for a specific user.
     *
     * @param int $userId The ID of the user.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index(int $userId)
    {
        $model = new ApprovedControlModel();
        $controls = $model->getApprovedControlsWithActivityDetails($userId);

        // N+1 Optimized: Fetch all budget items in one query
        $designIds = [];
        foreach ($controls as $control) {
            if (!empty($control['act_design_id'])) {
                $designIds[] = $control['act_design_id'];
            }
        }

        $allBudgetItems = [];
        if (!empty($designIds)) {
            $budgetModel = new ActivityBudgetItemsModel();
            $budgetResults = $budgetModel->whereIn('act_design_id', array_unique($designIds))->findAll();
            
            // Group them by act_design_id
            foreach ($budgetResults as $item) {
                $allBudgetItems[$item['act_design_id']][] = $item;
            }
        }

        
        $db = \Config\Database::connect();
        
        foreach ($controls as &$control) {
            $id = $control['act_design_id'] ?? null;
            
            if ($id) {
                // Fetch mandates and issues
                $mandates = $db->table('activity_design_mandates')->where('act_design_id', $id)->get()->getResultArray();
                $issues = $db->table('activity_design_issues')->where('act_design_id', $id)->get()->getResultArray();
                
                $control['gad_mandate_ids'] = implode(',', array_column($mandates, 'mandate_id'));
                $control['gender_issue_ids'] = implode(',', array_column($issues, 'issue_id'));
            }

            $id = $control['act_design_id'] ?? null;
            
            // Initialize defaults
            $control['meals_total'] = 0;
            $control['breakfast_selected'] = 0;
            $control['lunch_selected'] = 0;
            $control['dinner_selected'] = 0;
            $control['snacks_total'] = 0;
            $control['am_snack_selected'] = 0;
            $control['pm_snack_selected'] = 0;
            $control['function_room_venue'] = 0;
            $control['accommodation'] = 0;
            $control['equipment_rental'] = 0;
            $control['professional_fee_honoria'] = 0;
            $control['tokens'] = 0;
            $control['materials_total'] = 0;
            $control['transportation'] = 0;
            $control['others_total'] = 0;
            $control['materials_others_breakdown'] = [];

            if ($id && isset($allBudgetItems[$id])) {
                $control['budget_items'] = $allBudgetItems[$id];
                $budgetMap = [
                    'Meals' => 'meals_total',
                    'Snacks' => 'snacks_total',
                    'Function Room/Venue' => 'function_room_venue',
                    'Accommodation' => 'accommodation',
                    'Equipment Rental' => 'equipment_rental',
                    'Professional Fee/Honoria' => 'professional_fee_honoria',
                    'Professional Fee/Honoraria' => 'professional_fee_honoria',
                    'Token/s' => 'tokens',
                    'Materials and Supplies' => 'materials_total',
                    'Transportation' => 'transportation',
                ];
                
                foreach ($allBudgetItems[$id] as $item) {
                    $name = $item['item_name'];
                    if (isset($budgetMap[$name])) {
                        $control[$budgetMap[$name]] = $item['amount'];
                        if ($budgetMap[$name] === 'professional_fee_honoria') {
                            $control['pf_pax'] = $item['pax'];
                        }
                        if ($budgetMap[$name] === 'tokens') {
                            $control['tokens_pax'] = $item['pax'];
                        }
                        
                        if ($name === 'Meals' && !empty($item['sub_item'])) {
                            $control['breakfast_selected'] = strpos(strtolower($item['sub_item']), 'breakfast') !== false ? 1 : 0;
                            $control['lunch_selected'] = strpos(strtolower($item['sub_item']), 'lunch') !== false ? 1 : 0;
                            $control['dinner_selected'] = strpos(strtolower($item['sub_item']), 'dinner') !== false ? 1 : 0;
                        }
                        if ($name === 'Snacks' && !empty($item['sub_item'])) {
                            $control['am_snack_selected'] = strpos(strtolower($item['sub_item']), 'am') !== false ? 1 : 0;
                            $control['pm_snack_selected'] = strpos(strtolower($item['sub_item']), 'pm') !== false ? 1 : 0;
                        }
                    } elseif ($name === 'Others') {
                        $control['others_total'] += $item['amount'];
                        if (!empty($item['sub_item'])) {
                            $control['materials_others_breakdown'][] = [
                                'name' => $item['sub_item'],
                                'amount' => $item['amount']
                            ];
                        }
                    }
                }
                
                if (!empty($control['materials_others_breakdown'])) {
                    $control['materials_others_breakdown'] = json_encode($control['materials_others_breakdown']);
                } else {
                    $control['materials_others_breakdown'] = null;
                }

            } else {
                $control['budget_items'] = [];
            }
        }


        return $this->respond([
            'success' => true,
            'message' => 'Approved control numbers fetched successfully.',
            'data'    => $controls,
        ]);
    }
}