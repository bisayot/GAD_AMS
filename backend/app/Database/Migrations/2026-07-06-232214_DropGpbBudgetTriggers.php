<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropGpbBudgetTriggers extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("DROP TRIGGER IF EXISTS trg_sync_budget;");
        $db->query("DROP TRIGGER IF EXISTS trg_sync_budget_delete;");
        $db->query("DROP TRIGGER IF EXISTS trg_sync_budget_update;");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        $db->query("
            CREATE TRIGGER `trg_sync_budget` AFTER INSERT ON `gpb_budget_breakdown` FOR EACH ROW BEGIN
                UPDATE gad_plan_budget
                SET budget_breakdown = (
                    SELECT GROUP_CONCAT(CONCAT(category, ': ', amount) SEPARATOR ', ')
                    FROM gpb_budget_breakdown
                    WHERE gpb_id = NEW.gpb_id
                )
                WHERE gpb_id = NEW.gpb_id;
            END
        ");
        
        $db->query("
            CREATE TRIGGER `trg_sync_budget_delete` AFTER DELETE ON `gpb_budget_breakdown` FOR EACH ROW BEGIN
                UPDATE gad_plan_budget
                SET budget_breakdown = (
                    SELECT GROUP_CONCAT(CONCAT(category, ': ', amount) SEPARATOR ', ')
                    FROM gpb_budget_breakdown
                    WHERE gpb_id = OLD.gpb_id
                )
                WHERE gpb_id = OLD.gpb_id;
            END
        ");
        
        $db->query("
            CREATE TRIGGER `trg_sync_budget_update` AFTER UPDATE ON `gpb_budget_breakdown` FOR EACH ROW BEGIN
                UPDATE gad_plan_budget
                SET budget_breakdown = (
                    SELECT GROUP_CONCAT(CONCAT(category, ': ', amount) SEPARATOR ', ')
                    FROM gpb_budget_breakdown
                    WHERE gpb_id = NEW.gpb_id
                )
                WHERE gpb_id = NEW.gpb_id;
            END
        ");
    }
}
