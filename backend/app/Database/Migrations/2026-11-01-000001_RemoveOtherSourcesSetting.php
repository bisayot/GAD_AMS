<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveOtherSourcesSetting extends Migration
{
    public function up()
    {
        $this->db->table('settings')->where('key', 'otherSources')->delete();
    }

    public function down()
    {
        // The value is now derived from budget lines and cannot be restored safely.
    }
}
