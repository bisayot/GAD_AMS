<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeBudgetItems extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Create the new budget_categories table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('budget_categories', true);

        // Populate basic categories
        $categories = [
            ['name' => 'Meals and Snacks'],
            ['name' => 'Function Room / Venue'],
            ['name' => 'Accommodation'],
            ['name' => 'Equipment Rental'],
            ['name' => 'Professional Fee / Honoraria'],
            ['name' => 'Tokens'],
            ['name' => 'Materials and Supplies'],
            ['name' => 'Transportation'],
            ['name' => 'Other Expenses'],
        ];
        $db->table('budget_categories')->insertBatch($categories);

        // 2. Rename old tables to _old
        if ($db->tableExists('activity_budget_items')) {
            $db->query('RENAME TABLE activity_budget_items TO activity_budget_items_old');
        }
        if ($db->tableExists('accomplishment_budget_items')) {
            $db->query('RENAME TABLE accomplishment_budget_items TO accomplishment_budget_items_old');
        }

        // 3. Create new normalized activity_budget_items
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'act_design_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'category_id' => [
                'type' => 'INT',
                'null' => true, // Allows custom if no specific ID maps
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'sub_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pax' => [
                'type' => 'INT',
                'null' => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => '0.00',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activity_budget_items', true);

        // 4. Create new normalized accomplishment_budget_items
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'accomplishment_report_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'category_id' => [
                'type' => 'INT',
                'null' => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'sub_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pax' => [
                'type' => 'INT',
                'null' => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => '0.00',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('accomplishment_budget_items', true);

        // 5. Migrate Data from _old tables to the new normalized structure
        $this->migrateOldData('activity_budget_items_old', 'activity_budget_items', 'act_design_id');
        $this->migrateOldData('accomplishment_budget_items_old', 'accomplishment_budget_items', 'accomplishment_report_id');
        
        // 6. Drop old tables
        $this->forge->dropTable('activity_budget_items_old', true);
        $this->forge->dropTable('accomplishment_budget_items_old', true);
    }

    private function migrateOldData($oldTable, $newTable, $fkName)
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists($oldTable)) return;

        $oldRows = $db->table($oldTable)->get()->getResultArray();
        $categories = $db->table('budget_categories')->get()->getResultArray();
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat['name']] = $cat['id'];
        }

        $inserts = [];
        
        $fieldsToMap = [
            'meals_and_snacks' => 'Meals and Snacks',
            'function_room_venue' => 'Function Room / Venue',
            'accommodation' => 'Accommodation',
            'equipment_rental' => 'Equipment Rental',
            'professional_fee_honoria' => 'Professional Fee / Honoraria',
            'tokens' => 'Tokens',
            'materials_and_supplies' => 'Materials and Supplies',
            'transportation' => 'Transportation',
            'others_total' => 'Other Expenses'
        ];

        foreach ($oldRows as $row) {
            foreach ($fieldsToMap as $column => $catName) {
                if (isset($row[$column]) && (float)$row[$column] > 0) {
                    $inserts[] = [
                        $fkName => $row[$fkName],
                        'category_id' => $catMap[$catName] ?? null,
                        'item_name' => $catName,
                        'sub_item' => null,
                        'pax' => null,
                        'amount' => (float)$row[$column]
                    ];
                }
            }
        }

        if (!empty($inserts)) {
            $db->table($newTable)->insertBatch($inserts);
        }
    }

    public function down()
    {
        // Not implementing down for complex normalization
    }
}
