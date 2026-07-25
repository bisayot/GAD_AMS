<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddModificationFieldsToActivityDesign extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activity_design', [
            'modification_request_status' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'pending', 'approved', 'rejected'],
                'default'    => 'none',
                'null'       => false,
            ],
            'modification_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_modified' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_design', ['modification_request_status', 'modification_remarks', 'is_modified']);
    }
}
