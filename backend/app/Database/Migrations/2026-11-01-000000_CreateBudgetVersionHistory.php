<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBudgetVersionHistory extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('budget_versions')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'fiscal_year' => ['type' => 'INT', 'constraint' => 11],
                'version_number' => ['type' => 'INT', 'constraint' => 11],
                'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
                'source' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'initial'],
                'organization_data' => ['type' => 'LONGTEXT', 'null' => true],
                'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'closed_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey(['fiscal_year', 'status']);
            $this->forge->createTable('budget_versions');
        }

        if ($this->db->tableExists('gpb_items')) {
            $fields = $this->db->getFieldNames('gpb_items');
            if (!in_array('plan_version_id', $fields, true)) {
                $this->forge->addColumn('gpb_items', [
                    'plan_version_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'id'],
                ]);
            }
            if (!in_array('item_key', $fields, true)) {
                $this->forge->addColumn('gpb_items', [
                    'item_key' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true, 'after' => 'plan_version_id'],
                ]);
            }

            $years = $this->db->table('gpb_items')->select('fiscal_year')->distinct()->get()->getResultArray();
            foreach ($years as $yearRow) {
                $year = (int)$yearRow['fiscal_year'];
                $version = $this->db->table('budget_versions')
                    ->where(['fiscal_year' => $year, 'status' => 'active'])->get()->getRowArray();
                if (!$version) {
                    $this->db->table('budget_versions')->insert([
                        'fiscal_year' => $year, 'version_number' => 1, 'status' => 'active',
                        'source' => 'initial', 'organization_data' => json_encode(['year' => $year]),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $version = ['id' => $this->db->insertID()];
                }
                $rows = $this->db->table('gpb_items')->where('fiscal_year', $year)->get()->getResultArray();
                foreach ($rows as $row) {
                    $this->db->table('gpb_items')->where('id', $row['id'])->update([
                        'plan_version_id' => $version['id'],
                        'item_key' => 'legacy-' . $year . '-' . $row['id'],
                    ]);
                }
            }

            if (!$this->indexExists('gpb_items', 'idx_gpb_items_item_key')) {
                $this->db->query('CREATE INDEX idx_gpb_items_item_key ON gpb_items (item_key)');
            }
            if (!$this->indexExists('gpb_items', 'uq_gpb_items_version_item_key')) {
                $this->db->query('CREATE UNIQUE INDEX uq_gpb_items_version_item_key ON gpb_items (plan_version_id, item_key)');
            }
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $rows = $this->db->query('SHOW INDEX FROM ' . $this->db->escapeIdentifiers($table))->getResultArray();
        foreach ($rows as $row) {
            if (($row['Key_name'] ?? '') === $index) {
                return true;
            }
        }
        return false;
    }

    public function down()
    {
        if ($this->db->tableExists('gpb_items')) {
            $this->db->query('DROP INDEX uq_gpb_items_version_item_key ON gpb_items');
            $this->db->query('DROP INDEX idx_gpb_items_item_key ON gpb_items');
            $this->forge->dropColumn('gpb_items', ['plan_version_id', 'item_key']);
        }
        $this->forge->dropTable('budget_versions', true);
    }
}
