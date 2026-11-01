<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddActivityDesignRequestType extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activity_design', [
            'request_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'modification',
                'null'       => false,
                'after'      => 'modification_request_status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_design', 'request_type');
    }
}
