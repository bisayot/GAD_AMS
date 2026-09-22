<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityDesignVenuesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'act_design_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'venue_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activity_design_venues', true);
    }

    public function down()
    {
        $this->forge->dropTable('activity_design_venues', true);
    }
}
