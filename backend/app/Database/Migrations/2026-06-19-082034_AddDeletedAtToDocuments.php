<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToDocuments extends Migration
{
    public function up()
    {
        // Add deleted_at to activity_design
        $this->forge->addColumn('activity_design', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ]
        ]);

        // Add deleted_at to accomplishment_report
        $this->forge->addColumn('accomplishment_report', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_design', 'deleted_at');
        $this->forge->dropColumn('accomplishment_report', 'deleted_at');
    }
}
