<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToAccomplishmentReports extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('updated_at', 'accomplishment_report')) {
            $this->forge->addColumn('accomplishment_report', [
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'default' => null,
                    'after' => 'created_at',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('updated_at', 'accomplishment_report')) {
            $this->forge->dropColumn('accomplishment_report', 'updated_at');
        }
    }
}
