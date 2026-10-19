<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLoginLockoutFieldsToUsers extends Migration
{
    public function up()
    {
        $columns = [];

        if (!$this->db->fieldExists('failed_login_attempts', 'users')) {
            $columns['failed_login_attempts'] = [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default' => 0,
            ];
        }

        if (!$this->db->fieldExists('locked_until', 'users')) {
            $columns['locked_until'] = [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ];
        }

        if ($columns) {
            $this->forge->addColumn('users', $columns);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'failed_login_attempts',
            'locked_until',
        ]);
    }
}
