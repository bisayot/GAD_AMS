<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ConsolidateUserProfiles extends Migration
{
    public function up()
    {
        // 1. Add fields to users table
        $fields = [
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'profile_role' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
        ];
        $this->forge->addColumn('users', $fields);

        // 2. Transfer data from user_profiles to users
        $db = \Config\Database::connect();
        if ($db->tableExists('user_profiles')) {
            $db->query("
                UPDATE users u
                JOIN user_profiles up ON u.id = up.user_id
                SET u.first_name = up.first_name,
                    u.middle_name = up.middle_name,
                    u.last_name = up.last_name,
                    u.profile_role = up.user_role
            ");

            // 3. Drop user_profiles table
            $this->forge->dropTable('user_profiles', true);
        }
    }

    public function down()
    {
        // Recreate user_profiles
        $this->forge->addField([
            'user_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'middle_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'user_role' => ['type' => 'ENUM', 'constraint' => ['Director','Staff','TWG','Non-TWG'], 'null' => true],
            'office_unit_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_profiles');

        // Copy data back
        $db = \Config\Database::connect();
        $db->query("
            INSERT INTO user_profiles (user_id, first_name, middle_name, last_name, user_role, office_unit_id)
            SELECT id, first_name, middle_name, last_name, profile_role, office_id FROM users WHERE first_name IS NOT NULL
        ");

        // Drop columns from users
        $this->forge->dropColumn('users', ['first_name', 'middle_name', 'last_name', 'profile_role']);
    }
}
