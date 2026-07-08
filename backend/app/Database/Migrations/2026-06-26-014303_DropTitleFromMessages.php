<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropTitleFromMessages extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('messages', 'title');
    }

    public function down()
    {
        $this->forge->addColumn('messages', [
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);
    }
}
