<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateArchivedAnnualReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'fiscal_year' => [
                'type' => 'VARCHAR',
                'constraint' => 4,
            ],
            'html_content' => [
                'type' => 'LONGTEXT',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->createTable('archived_annual_reports');
    }

    public function down()
    {
        $this->forge->dropTable('archived_annual_reports');
    }
}
