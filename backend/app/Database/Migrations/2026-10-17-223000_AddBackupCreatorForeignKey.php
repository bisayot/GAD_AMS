<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBackupCreatorForeignKey extends Migration
{
    public function up()
    {
        $this->db->query(
            'ALTER TABLE `backups` MODIFY `created_by` BIGINT UNSIGNED NULL'
        );
        $this->db->query(
            'ALTER TABLE `backups` ADD CONSTRAINT `fk_backups_created_by` '
            . 'FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) '
            . 'ON DELETE SET NULL ON UPDATE CASCADE'
        );
    }

    public function down()
    {
        $this->forge->dropForeignKey('backups', 'fk_backups_created_by');
    }
}
