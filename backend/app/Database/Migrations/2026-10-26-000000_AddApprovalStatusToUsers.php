<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovalStatusToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('approval_status', 'users')) {
            $this->forge->addColumn('users', [
                'approval_status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'approved',
                    'after'      => 'profile_role',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'approval_status');
    }
}
