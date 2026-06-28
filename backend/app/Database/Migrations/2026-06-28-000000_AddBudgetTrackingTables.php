<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBudgetTrackingTables extends Migration
{
    public function up()
    {
        // 1. archived_accomplishment_budget_items
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'accomplishment_report_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'sub_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pax' => [
                'type' => 'INT',
                'null' => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => '0.00',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('archived_accomplishment_budget_items');

        // 2. archived_activity_budget_items
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'act_design_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'sub_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pax' => [
                'type' => 'INT',
                'null' => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => '0.00',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('archived_activity_budget_items');

        // 3. budget_realignment_logs
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'reference_no' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'gpb_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['augmentation', 'realignment'],
                'null'       => false,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
            ],
            'justification' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true, // Allows default current_timestamp in CI4
            ],
        ]);
        $this->forge->addKey('id', true);
        // We'll skip foreign key to avoid issues if gad_plan_budget lacks exact keys in some setups
        $this->forge->createTable('budget_realignment_logs');
    }

    public function down()
    {
        $this->forge->dropTable('archived_accomplishment_budget_items');
        $this->forge->dropTable('archived_activity_budget_items');
        $this->forge->dropTable('budget_realignment_logs');
    }
}
