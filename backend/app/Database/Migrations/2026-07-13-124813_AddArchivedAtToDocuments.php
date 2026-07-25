<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArchivedAtToDocuments extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('archived_at', 'activity_design')) {
            $this->forge->addColumn('activity_design', [
                'archived_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'default' => null,
                ],
            ]);
        }

        if (!$this->db->fieldExists('archived_at', 'accomplishment_report')) {
            $this->forge->addColumn('accomplishment_report', [
                'archived_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'default' => null,
                ],
            ]);
        }
        
        // Populate existing ones with current timestamp as fallback
        $db = \Config\Database::connect();
        $db->table('activity_design')->where('is_archived', 1)->update(['archived_at' => date('Y-m-d H:i:s')]);
        $db->table('accomplishment_report')->where('is_archived', 1)->update(['archived_at' => date('Y-m-d H:i:s')]);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_design', 'archived_at');
        $this->forge->dropColumn('accomplishment_report', 'archived_at');
    }
}
