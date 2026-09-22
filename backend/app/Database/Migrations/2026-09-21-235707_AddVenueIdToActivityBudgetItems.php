<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVenueIdToActivityBudgetItems extends Migration
{
    public function up()
    {
        $fields = [
            'venue_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'act_design_id'
            ]
        ];
        $this->forge->addColumn('activity_budget_items', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_budget_items', 'venue_id');
    }
}
