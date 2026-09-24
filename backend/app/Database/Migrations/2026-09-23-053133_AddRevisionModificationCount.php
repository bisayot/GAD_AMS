<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRevisionModificationCount extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activity_design', [
            'revision_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
            ],
            'modification_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
            ],
        ]);

        $this->forge->addColumn('accomplishment_report', [
            'revision_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_design', ['revision_count', 'modification_count']);
        $this->forge->dropColumn('accomplishment_report', 'revision_count');
    }
}
