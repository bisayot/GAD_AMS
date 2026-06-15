<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class BudgetController extends Controller
{
    use ResponseTrait;

    /**
     * Get overall budget utilization summary metrics.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getSummary()
    {
        $db = \Config\Database::connect();
        
        $totalBudget = $db->table('gad_plan_budget')
            ->selectSum('gad_budget')
            ->get()->getRow()->gad_budget ?? 0.0;

        $totalUtilized = $db->table('archived_activity_designs')
            ->selectSum('proposed_budget')
            ->get()->getRow()->proposed_budget ?? 0.0;

        $totalBudget = (float) $totalBudget;
        $totalUtilized = (float) $totalUtilized;
        $remainingBalance = $totalBudget - $totalUtilized;
        $utilizationRate = $totalBudget > 0 ? ($totalUtilized / $totalBudget) * 100 : 0.0;

        return $this->respond([
            'success' => true,
            'data' => [
                'total_budget'      => $totalBudget,
                'total_utilized'    => $totalUtilized,
                'remaining_balance' => $remainingBalance,
                'utilization_rate'  => $utilizationRate
            ]
        ]);
    }

    /**
     * Get the dynamic GAD Plan Budget rows grouped and compiled with breakdown source metadata.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getGadPlan()
    {
        $db = \Config\Database::connect();
        
        $rows = $db->table('gad_plan_budget gpb')
            ->select('gpb.*, (SELECT GROUP_CONCAT(DISTINCT source_of_budget SEPARATOR ", ") FROM gpb_budget_breakdown WHERE gpb_id = gpb.gpb_id) AS source')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['gad_budget'] = (float)$row['gad_budget'];
        }

        return $this->respond([
            'success' => true,
            'data'    => $rows
        ]);
    }

    /**
     * Handle CORS preflight OPTIONS requests.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function optionsHandler()
    {
        return $this->respond(['status' => 200]);
    }
}
