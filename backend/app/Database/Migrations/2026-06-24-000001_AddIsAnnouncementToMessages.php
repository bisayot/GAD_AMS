<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsAnnouncementToMessages extends Migration
{
    public function up()
    {
        $fields = [
            'is_announcement' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ];
        $this->forge->addColumn('messages', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('messages', 'is_announcement');
    }
}
