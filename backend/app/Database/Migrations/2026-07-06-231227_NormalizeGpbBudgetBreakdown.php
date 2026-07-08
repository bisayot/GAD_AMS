<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeGpbBudgetBreakdown extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // 1. Check if we need to migrate data
        if ($db->tableExists('gad_plan_budget') && $db->tableExists('gpb_budget_breakdown')) {
            $plans = $db->table('gad_plan_budget')->get()->getResultArray();
            $inserts = [];
            
            // Delete existing rows just to be safe so we don't duplicate if run multiple times
            $db->table('gpb_budget_breakdown')->truncate();

            foreach ($plans as $plan) {
                if (!empty($plan['budget_breakdown'])) {
                    $text = $plan['budget_breakdown'];
                    
                    // Match pattern: string followed by number with decimals (e.g. "Meals and Snack 318,800.00")
                    if (preg_match_all('/(.*?)\s+([\d,\.]+\d{2})/', $text, $matches)) {
                        for ($i = 0; $i < count($matches[0]); $i++) {
                            $category = trim($matches[1][$i]);
                            $amountStr = str_replace(',', '', $matches[2][$i]);
                            $amount = (float) $amountStr;
                            
                            $inserts[] = [
                                'gpb_id' => $plan['gpb_id'],
                                'category' => $category,
                                'amount' => $amount
                            ];
                        }
                    } else {
                        // Fallback: if it doesn't match the regex nicely, just insert it as a single row
                        $inserts[] = [
                            'gpb_id' => $plan['gpb_id'],
                            'category' => trim($text),
                            'amount' => 0
                        ];
                    }
                }
            }
            
            if (!empty($inserts)) {
                $db->table('gpb_budget_breakdown')->insertBatch($inserts);
            }
        }
        
        // 2. Drop the column from gad_plan_budget
        if ($db->fieldExists('budget_breakdown', 'gad_plan_budget')) {
            $this->forge->dropColumn('gad_plan_budget', 'budget_breakdown');
        }
    }

    public function down()
    {
        // 1. Restore the column
        $this->forge->addColumn('gad_plan_budget', [
            'budget_breakdown' => [
                'type' => 'TEXT',
                'null' => true,
            ]
        ]);
        
        // 2. Try to reverse migrate
        $db = \Config\Database::connect();
        if ($db->tableExists('gpb_budget_breakdown')) {
            $breakdowns = $db->table('gpb_budget_breakdown')->orderBy('gpb_id')->get()->getResultArray();
            $grouped = [];
            
            foreach ($breakdowns as $b) {
                $id = $b['gpb_id'];
                if (!isset($grouped[$id])) {
                    $grouped[$id] = [];
                }
                $amtStr = number_format($b['amount'], 2);
                $grouped[$id][] = $b['category'] . ' ' . $amtStr;
            }
            
            foreach ($grouped as $id => $items) {
                $text = implode(' ', $items);
                $db->table('gad_plan_budget')
                   ->where('gpb_id', $id)
                   ->update(['budget_breakdown' => $text]);
            }
        }
    }
}
