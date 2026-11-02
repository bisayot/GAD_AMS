<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArDeadlineAndLateTracking extends Migration
{
    public function up()
    {
        // Add is_late and submission_deadline to accomplishment_report
        $fieldsToAdd = [];

        if (!$this->db->fieldExists('is_late', 'accomplishment_report')) {
            $fieldsToAdd['is_late'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
            ];
        }

        if (!$this->db->fieldExists('submission_deadline', 'accomplishment_report')) {
            $fieldsToAdd['submission_deadline'] = [
                'type'    => 'DATE',
                'null'    => true,
                'default' => null,
                'after'   => 'is_late',
            ];
        }

        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('accomplishment_report', $fieldsToAdd);
        }

        // Create ar_deadline_notifications table to track sent emails and prevent duplicates
        if (!$this->db->tableExists('ar_deadline_notifications')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'act_design_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'milestone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'recipient_user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'sent_at' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey(['act_design_id', 'milestone', 'recipient_user_id'], false, false, 'idx_ad_milestone_recipient');
            $this->forge->createTable('ar_deadline_notifications', true);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('is_late', 'accomplishment_report')) {
            $this->forge->dropColumn('accomplishment_report', 'is_late');
        }

        if ($this->db->fieldExists('submission_deadline', 'accomplishment_report')) {
            $this->forge->dropColumn('accomplishment_report', 'submission_deadline');
        }

        if ($this->db->tableExists('ar_deadline_notifications')) {
            $this->forge->dropTable('ar_deadline_notifications', true);
        }
    }
}

