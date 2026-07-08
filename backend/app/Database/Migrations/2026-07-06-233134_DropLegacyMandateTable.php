<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropLegacyMandateTable extends Migration
{
    public function up()
    {
        $this->forge->dropTable('mandate', true);
    }

    public function down()
    {
        // Recreate the empty mandate table if rolling back
        $this->forge->addField([
            'mandate_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'mandate_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'gpb_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('mandate_id', true);
        // The original table had an index on gpb_id but it wasn't a strict foreign key
        $this->forge->addKey('gpb_id');
        $this->forge->createTable('mandate', true);
    }
}
