<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterMessagesDocumentId extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('messages', [
            'document_id' => [
                'type' => 'TEXT',
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('messages', [
            'document_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ]
        ]);
    }
}
