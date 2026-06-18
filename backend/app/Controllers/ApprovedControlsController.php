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

        // Fetch budget items for each activity design
        $budgetModel = new ActivityBudgetItemsModel();
        foreach ($controls as &$control) {
            if (!empty($control['original_act_design_id'])) {
                $budgetItems = $budgetModel->where('act_design_id', $control['original_act_design_id'])->findAll();
                $control['budget_items'] = $budgetItems;
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