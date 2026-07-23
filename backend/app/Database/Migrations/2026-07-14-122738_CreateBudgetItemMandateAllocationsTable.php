<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBudgetItemMandateAllocationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'budget_item_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'item_type' => [
                'type' => 'ENUM',
                'constraint' => ['AD', 'AR'],
                'null' => false,
            ],
            'mandate_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'allocated_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey(['budget_item_id', 'item_type']);
        $this->forge->addKey('mandate_id');
        $this->forge->createTable('budget_item_mandate_allocations');
    }

    public function down()
    {
        $this->forge->dropTable('budget_item_mandate_allocations');
    }
}
