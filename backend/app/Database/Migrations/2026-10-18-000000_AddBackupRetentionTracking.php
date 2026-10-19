<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBackupRetentionTracking extends Migration
{
    public function up()
    {
        $columns = [];

        if (!$this->db->fieldExists('expires_at', 'backups')) {
            $columns['expires_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'created_at',
            ];
        }

        if (!$this->db->fieldExists('cleanup_attempts', 'backups')) {
            $columns['cleanup_attempts'] = [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default' => 0,
                'after' => 'expires_at',
            ];
        }

        if (!$this->db->fieldExists('last_cleanup_error', 'backups')) {
            $columns['last_cleanup_error'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'cleanup_attempts',
            ];
        }

        if (!$this->db->fieldExists('drive_deleted_at', 'backups')) {
            $columns['drive_deleted_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'last_cleanup_error',
            ];
        }

        if ($columns) {
            $this->forge->addColumn('backups', $columns);
        }

        $this->db->query(
            'UPDATE `backups` SET `expires_at` = DATE_ADD(`created_at`, INTERVAL 14 DAY) '
            . 'WHERE `expires_at` IS NULL'
        );
    }

    public function down()
    {
        $this->forge->dropColumn('backups', [
            'expires_at',
            'cleanup_attempts',
            'last_cleanup_error',
            'drive_deleted_at',
        ]);
    }
}
