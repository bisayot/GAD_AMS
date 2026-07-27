<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGpbBudgetLineIdToAllocations extends Migration
{
    public function up()
    {
        $fields = [
            'gpb_budget_line_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'mandate_id'
            ],
        ];
        $this->forge->addColumn('budget_item_mandate_allocations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('budget_item_mandate_allocations', 'gpb_budget_line_id');
    }
}
