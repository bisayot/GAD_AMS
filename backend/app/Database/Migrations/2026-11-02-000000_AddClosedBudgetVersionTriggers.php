<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClosedBudgetVersionTriggers extends Migration
{
    public function up()
    {
        $triggers = [
            'trg_gpb_items_prevent_closed_insert' => "
                CREATE TRIGGER trg_gpb_items_prevent_closed_insert
                BEFORE INSERT ON gpb_items
                FOR EACH ROW
                BEGIN
                    DECLARE v_status VARCHAR(20);
                    IF NEW.plan_version_id IS NOT NULL THEN
                        SELECT status INTO v_status FROM budget_versions WHERE id = NEW.plan_version_id LIMIT 1;
                        IF v_status = 'closed' THEN
                            SIGNAL SQLSTATE '45000'
                                SET MESSAGE_TEXT = 'Cannot insert item into a closed budget version';
                        END IF;
                    END IF;
                END;
            ",
            'trg_gpb_items_prevent_closed_update' => "
                CREATE TRIGGER trg_gpb_items_prevent_closed_update
                BEFORE UPDATE ON gpb_items
                FOR EACH ROW
                BEGIN
                    DECLARE v_status VARCHAR(20);
                    IF OLD.plan_version_id IS NOT NULL THEN
                        SELECT status INTO v_status FROM budget_versions WHERE id = OLD.plan_version_id LIMIT 1;
                        IF v_status = 'closed' THEN
                            SIGNAL SQLSTATE '45000'
                                SET MESSAGE_TEXT = 'Cannot modify an item belonging to a closed budget version';
                        END IF;
                    END IF;
                END;
            ",
            'trg_gpb_items_prevent_closed_delete' => "
                CREATE TRIGGER trg_gpb_items_prevent_closed_delete
                BEFORE DELETE ON gpb_items
                FOR EACH ROW
                BEGIN
                    DECLARE v_status VARCHAR(20);
                    IF OLD.plan_version_id IS NOT NULL THEN
                        SELECT status INTO v_status FROM budget_versions WHERE id = OLD.plan_version_id LIMIT 1;
                        IF v_status = 'closed' THEN
                            SIGNAL SQLSTATE '45000'
                                SET MESSAGE_TEXT = 'Cannot delete an item belonging to a closed budget version';
                        END IF;
                    END IF;
                END;
            ",
        ];

        foreach ($triggers as $name => $sql) {
            try {
                $this->db->query("DROP TRIGGER IF EXISTS {$name}");
                $this->db->query($sql);
            } catch (\Throwable $e) {
                // In restricted shared hosting environments where TRIGGER privilege is not granted,
                // log warning and allow migration to pass. The PHP application guard (BudgetVersionService::assertItemModifiable)
                // serves as the strict application-level enforcement layer.
                log_message('warning', "Could not create trigger {$name}: " . $e->getMessage());
            }
        }
    }

    public function down()
    {
        $triggers = [
            'trg_gpb_items_prevent_closed_insert',
            'trg_gpb_items_prevent_closed_update',
            'trg_gpb_items_prevent_closed_delete',
        ];

        foreach ($triggers as $name) {
            try {
                $this->db->query("DROP TRIGGER IF EXISTS {$name}");
            } catch (\Throwable $e) {
                log_message('warning', "Could not drop trigger {$name}: " . $e->getMessage());
            }
        }
    }
}

