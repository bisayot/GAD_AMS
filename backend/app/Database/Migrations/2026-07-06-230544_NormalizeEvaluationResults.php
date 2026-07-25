<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeEvaluationResults extends Migration
{
    public function up()
    {
        // 1. Create the new normalized table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'accomplishment_report_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'question_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'score' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('accomplishment_report_id');
        $this->forge->createTable('evaluation_results');

        // 2. Migrate existing data from accomplishment_evaluation_results
        $db = \Config\Database::connect();
        if ($db->tableExists('accomplishment_evaluation_results')) {
            $oldResults = $db->table('accomplishment_evaluation_results')->get()->getResultArray();
            $inserts = [];
            foreach ($oldResults as $row) {
                $reportId = $row['accomplishment_report_id'];
                
                $questions = [
                    'time_management',
                    'orderliness_and_program_flow',
                    'appropriateness_of_venue',
                    'sound_system_and_hall_preparation',
                    'restrooms',
                    'food_and_drinks'
                ];
                
                foreach ($questions as $q) {
                    if (isset($row[$q])) {
                        $inserts[] = [
                            'accomplishment_report_id' => $reportId,
                            'question_key' => $q,
                            'score' => $row[$q]
                        ];
                    }
                }
            }
            if (!empty($inserts)) {
                $db->table('evaluation_results')->insertBatch($inserts);
            }

            // 3. Drop the old table
            $this->forge->dropTable('accomplishment_evaluation_results', true);
        }
    }

    public function down()
    {
        // Recreate the old table
        $this->forge->addField([
            'evaluation_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'accomplishment_report_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'time_management' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'default'    => '0.00',
            ],
            'orderliness_and_program_flow' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'default'    => '0.00',
            ],
            'appropriateness_of_venue' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'default'    => '0.00',
            ],
            'sound_system_and_hall_preparation' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'default'    => '0.00',
            ],
            'restrooms' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'default'    => '0.00',
            ],
            'food_and_drinks' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'default'    => '0.00',
            ],
        ]);
        $this->forge->addKey('evaluation_id', true);
        $this->forge->createTable('accomplishment_evaluation_results');

        // Migrate data back
        $db = \Config\Database::connect();
        if ($db->tableExists('evaluation_results')) {
            $newResults = $db->table('evaluation_results')->get()->getResultArray();
            $grouped = [];
            foreach ($newResults as $row) {
                $reportId = $row['accomplishment_report_id'];
                if (!isset($grouped[$reportId])) {
                    $grouped[$reportId] = [
                        'accomplishment_report_id' => $reportId,
                        'time_management' => 0,
                        'orderliness_and_program_flow' => 0,
                        'appropriateness_of_venue' => 0,
                        'sound_system_and_hall_preparation' => 0,
                        'restrooms' => 0,
                        'food_and_drinks' => 0,
                    ];
                }
                $key = $row['question_key'];
                if (isset($grouped[$reportId][$key])) {
                    $grouped[$reportId][$key] = $row['score'];
                }
            }
            if (!empty($grouped)) {
                $db->table('accomplishment_evaluation_results')->insertBatch(array_values($grouped));
            }
            $this->forge->dropTable('evaluation_results', true);
        }
    }
}
