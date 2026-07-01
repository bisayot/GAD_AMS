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
            if (!empty($control['original_act_design_id'])) {
                $designIds[] = $control['original_act_design_id'];
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

        foreach ($controls as &$control) {
            $id = $control['original_act_design_id'] ?? null;
            $control['budget_items'] = ($id && isset($allBudgetItems[$id])) ? $allBudgetItems[$id] : [];
        }

        return $this->respond([
            'success' => true,
            'message' => 'Approved control numbers fetched successfully.',
            'data'    => $controls,
        ]);
    }
}