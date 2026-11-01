<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBudgetVersionToAnnualReports extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('archived_annual_reports')) {
            return;
        }
        $fields = $this->db->getFieldNames('archived_annual_reports');
        if (!in_array('plan_version_id', $fields, true)) {
            $this->forge->addColumn('archived_annual_reports', [
                'plan_version_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'fiscal_year'],
                'version_number' => ['type' => 'INT', 'null' => true, 'after' => 'plan_version_id'],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('archived_annual_reports')) {
            $fields = $this->db->getFieldNames('archived_annual_reports');
            $columns = array_values(array_intersect(['plan_version_id', 'version_number'], $fields));
            if ($columns) {
                $this->forge->dropColumn('archived_annual_reports', $columns);
            }
        }
    }
}
