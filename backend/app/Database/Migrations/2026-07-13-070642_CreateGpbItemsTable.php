<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGpbItemsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('gpb_items')) return;
        
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fiscal_year' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'section' => [
                'type'       => 'ENUM',
                'constraint' => ['client_focused', 'organization_focused', 'attributed_program', 'client', 'org', 'attributed'],
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'mandate' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cause' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'objective' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'result' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ppa' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'mfo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'activity' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'targets' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'indicators' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'budget' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => '0.00',
            ],
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'office' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'responsible' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'budget_lines' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('gpb_items');
    }

    public function down()
    {
        $this->forge->dropTable('gpb_items');
    }
}
