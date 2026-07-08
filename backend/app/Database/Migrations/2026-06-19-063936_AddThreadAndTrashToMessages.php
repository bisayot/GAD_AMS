<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddThreadAndTrashToMessages extends Migration
{
    public function up()
    {
        $fields = [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'recipient_id'
            ],
            'deleted_by_sender_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by_recipient_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('messages', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('messages', ['parent_id', 'deleted_by_sender_at', 'deleted_by_recipient_at']);
    }
}
