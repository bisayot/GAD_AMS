<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexesToCoreTables extends Migration
{
    public function up()
    {
        // 1. Data Normalization: Clear empty strings and disambiguate soft-deleted accounts
        $this->db->query("UPDATE users SET email = NULL WHERE email = ''");
        $this->db->query("UPDATE users SET username = NULL WHERE role = 'deleted'");
        $this->db->query("UPDATE activity_design SET control_number = NULL WHERE control_number = ''");

        // 2. Add performance & integrity indexes to core tables
        // users table
        $this->addIndexIfNotExists('users', 'ALTER TABLE users ADD UNIQUE KEY uq_users_email (email)', 'uq_users_email');
        $this->addIndexIfNotExists('users', 'ALTER TABLE users ADD UNIQUE KEY uq_users_username (username)', 'uq_users_username');
        $this->addIndexIfNotExists('users', 'ALTER TABLE users ADD INDEX idx_users_student_id (student_id)', 'idx_users_student_id');
        $this->addIndexIfNotExists('users', 'ALTER TABLE users ADD INDEX idx_users_reset_token (reset_token)', 'idx_users_reset_token');

        // activity_design table
        $this->addIndexIfNotExists('activity_design', 'ALTER TABLE activity_design ADD UNIQUE KEY uq_ad_control_number (control_number)', 'uq_ad_control_number');
        $this->addIndexIfNotExists('activity_design', 'ALTER TABLE activity_design ADD INDEX idx_ad_status_archived_deleted (status, is_archived, deleted_at)', 'idx_ad_status_archived_deleted');
        $this->addIndexIfNotExists('activity_design', 'ALTER TABLE activity_design ADD INDEX idx_ad_start_date (start_date)', 'idx_ad_start_date');

        // accomplishment_report table
        $this->addIndexIfNotExists('accomplishment_report', 'ALTER TABLE accomplishment_report ADD INDEX idx_ar_status_archived_deleted (status, is_archived, deleted_at)', 'idx_ar_status_archived_deleted');
        $this->addIndexIfNotExists('accomplishment_report', 'ALTER TABLE accomplishment_report ADD INDEX idx_ar_start_date (start_date)', 'idx_ar_start_date');

        // activity_logs table
        $this->addIndexIfNotExists('activity_logs', 'ALTER TABLE activity_logs ADD INDEX idx_activity_logs_created_at (created_at)', 'idx_activity_logs_created_at');

        // gpb_items table
        $this->addIndexIfNotExists('gpb_items', 'ALTER TABLE gpb_items ADD INDEX idx_gpb_items_year_section (fiscal_year, section)', 'idx_gpb_items_year_section');

        // holidays table
        $this->addIndexIfNotExists('holidays', 'ALTER TABLE holidays ADD INDEX idx_holidays_date (date)', 'idx_holidays_date');
    }

    public function down()
    {
        $this->dropIndexIfExists('users', 'uq_users_email');
        $this->dropIndexIfExists('users', 'uq_users_username');
        $this->dropIndexIfExists('users', 'idx_users_student_id');
        $this->dropIndexIfExists('users', 'idx_users_reset_token');

        $this->dropIndexIfExists('activity_design', 'uq_ad_control_number');
        $this->dropIndexIfExists('activity_design', 'idx_ad_status_archived_deleted');
        $this->dropIndexIfExists('activity_design', 'idx_ad_start_date');

        $this->dropIndexIfExists('accomplishment_report', 'idx_ar_status_archived_deleted');
        $this->dropIndexIfExists('accomplishment_report', 'idx_ar_start_date');

        $this->dropIndexIfExists('activity_logs', 'idx_activity_logs_created_at');

        $this->dropIndexIfExists('gpb_items', 'idx_gpb_items_year_section');

        $this->dropIndexIfExists('holidays', 'idx_holidays_date');
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $res = $this->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName])->getResultArray();
        return !empty($res);
    }

    private function addIndexIfNotExists(string $table, string $sql, string $indexName): void
    {
        if (!$this->indexExists($table, $indexName)) {
            $this->db->query($sql);
        }
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if ($this->indexExists($table, $indexName)) {
            $this->db->query("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
        }
    }
}

