<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBudgetVersionMonitoringSnapshots extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('budget_version_monitoring_snapshots')) {
            return;
        }

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'plan_version_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'fiscal_year' => ['type' => 'INT', 'constraint' => 11],
            'total_budget' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'total_utilized' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'total_pending_approved' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'remaining_balance' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'default' => 0],
            'utilization_rate' => ['type' => 'DECIMAL', 'constraint' => '10,4', 'default' => 0],
            'rows_data' => ['type' => 'LONGTEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('plan_version_id');
        $this->forge->createTable('budget_version_monitoring_snapshots');

        $versions = $this->db->table('budget_versions')->get()->getResultArray();
        $service = new \App\Libraries\BudgetVersionService($this->db);
        foreach ($versions as $version) {
            $service->captureMonitoringSnapshot((int)$version['id'], (int)$version['fiscal_year']);
        }
    }

    public function down()
    {
        $this->forge->dropTable('budget_version_monitoring_snapshots', true);
    }
}
