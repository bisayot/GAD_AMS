<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdvancedFieldsToActivityBudgetItems extends Migration
{
    public function up()
    {
        $fields = [
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'default'    => '0.00',
                'after'      => 'pax'
            ],
            'multipliers' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'unit_cost'
            ],
            'formula' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'multipliers'
            ]
        ];
        $this->forge->addColumn('activity_budget_items', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_budget_items', 'unit_cost');
        $this->forge->dropColumn('activity_budget_items', 'multipliers');
        $this->forge->dropColumn('activity_budget_items', 'formula');
    }
}
