<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBackupsTable extends Migration
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
            'drive_file_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'size_bytes' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'default' => 0,
            ],
            'backup_type' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'backup',
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('drive_file_id');
        $this->forge->addKey('created_by');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('backups', true);
    }

    public function down()
    {
        $this->forge->dropTable('backups', true);
    }
}
