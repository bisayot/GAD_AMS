<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ImplementSoftDeletes extends Migration
{
    public function up()
    {
        // 1. Add is_archived to both tables, add control_number to activity_design
        $this->forge->addColumn('activity_design', [
            'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'control_number' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true]
        ]);

        $this->forge->addColumn('accomplishment_report', [
            'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]
        ]);

        $db = \Config\Database::connect();

        // 2. Transfer control numbers from the control_number table to activity_design
        if ($db->tableExists('control_number')) {
            $db->query("
                UPDATE activity_design ad
                JOIN control_number cn ON ad.act_design_id = cn.act_design_id
                SET ad.control_number = cn.control_number
            ");
            // Drop control_number table
            $this->forge->dropTable('control_number', true);
        }

        // 3. Migrate archived_activity_designs data
        // For columns that exist in both tables. We will assume the structure is mostly identical.
        if ($db->tableExists('archived_activity_designs')) {
            // First we need to get columns that exist in both tables to construct a safe INSERT SELECT
            $fields = $db->getFieldNames('activity_design');
            // We'll skip act_design_id and let it auto-increment, or actually we could keep the original ID.
            // In the archive table, it is stored as 'original_act_design_id'. Let's check if we can just insert it.
            // Since soft delete implies keeping the SAME record id, wait, the controller WAS creating new records
            // because it did unset($archiveData['act_design_id']);. So the original IDs are already gone from activity_design!
            // If they are gone from activity_design, we must INSERT them back using original_act_design_id as act_design_id.
            
            // Wait, does activity_design still have the record? ArchiveController DOES NOT delete from activity_design!
            // Let's look at ArchiveController.php line 84:
            // $db->table('archived_activity_designs')->insert($archiveData);
            // ... wait, IT DOES NOT DELETE from activity_design!
            // So if it was archived, it STILL EXISTS in activity_design!
            // If it still exists, all we have to do is UPDATE activity_design SET is_archived = 1 WHERE act_design_id IN (SELECT original_act_design_id FROM archived_activity_designs)!
            
            $db->query("
                UPDATE activity_design
                SET is_archived = 1
                WHERE act_design_id IN (SELECT original_act_design_id FROM archived_activity_designs)
            ");
            
            $this->forge->dropTable('archived_activity_designs', true);
        }

        // 4. Migrate archived_accomplishment_reports
        if ($db->tableExists('archived_accomplishment_reports')) {
            // Similarly, the controller didn't delete from accomplishment_report. It just inserted a copy.
            $db->query("
                UPDATE accomplishment_report
                SET is_archived = 1
                WHERE id IN (SELECT original_report_id FROM archived_accomplishment_reports)
            ");
            
            $this->forge->dropTable('archived_accomplishment_reports', true);
        }

        // 5. Drop empty child tables
        $this->forge->dropTable('archived_activity_budget_items', true);
        $this->forge->dropTable('archived_activity_design_issues', true);
        $this->forge->dropTable('archived_activity_design_mandates', true);
        $this->forge->dropTable('archived_accomplishment_budget_items', true);
        // Wait, evaluation results wasn't prefixed with archived in show tables?
        // Let's check SHOW TABLES again: accomplishment_evaluation_results. No archived_ version exists.
    }

    public function down()
    {
        $this->forge->dropColumn('activity_design', ['is_archived', 'control_number']);
        $this->forge->dropColumn('accomplishment_report', ['is_archived']);
        // We won't recreate the tables in down() for this complex refactor
    }
}
