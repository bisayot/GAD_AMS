<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsInsideBsuToVenuesAndSubmissions extends Migration
{
    public function up()
    {
        // Add to venues table
        $this->forge->addColumn('venues', [
            'is_inside_bsu' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
                'null'       => false,
            ],
        ]);

        // Add to activity_design table
        $this->forge->addColumn('activity_design', [
            'is_inside_bsu' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
                'null'       => false,
            ],
        ]);

        // Add to accomplishment_report table
        $this->forge->addColumn('accomplishment_report', [
            'is_inside_bsu' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('venues', 'is_inside_bsu');
        $this->forge->dropColumn('activity_design', 'is_inside_bsu');
        $this->forge->dropColumn('accomplishment_report', 'is_inside_bsu');
    }
}

