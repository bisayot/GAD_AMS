<?php

namespace App\Libraries;

use App\Models\SettingModel;
use App\Models\ActivityLogModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class DataRetentionService
{
    /**
     * Run the automated data cleanup based on TTL settings.
     * Optionally force execution even if it ran recently.
     */
    public static function runCleanup($force = false)
    {
        $settingModel = new SettingModel();
        
        // 1. Check if it already ran in the last 24 hours
        $lastRunSetting = $settingModel->where('key', 'last_cleanup_run')->first();
        $lastRun = $lastRunSetting ? $lastRunSetting['value'] : null;
        
        if (!$force && $lastRun) {
            $lastRunDate = strtotime($lastRun);
            // If ran within the last 24 hours, skip
            if (time() - $lastRunDate < 86400) {
                return false;
            }
        }

        // 2. Fetch TTL Settings (fallback to defaults)
        $sysSettingsRow = $settingModel->where('key', 'system_settings')->first();
        $settings = $sysSettingsRow && !empty($sysSettingsRow['value']) 
            ? (is_string($sysSettingsRow['value']) ? json_decode($sysSettingsRow['value'], true) : $sysSettingsRow['value']) 
            : [];
            
        $trashTtlDays = isset($settings['trash_ttl_days']) ? (int)$settings['trash_ttl_days'] : 30;
        $messagesTtlDays = isset($settings['messages_ttl_days']) ? (int)$settings['messages_ttl_days'] : 365;
        $logsTtlDays = isset($settings['activity_logs_ttl_days']) ? (int)$settings['activity_logs_ttl_days'] : 365;
        $archivesTtlDays = isset($settings['archived_documents_ttl_days']) ? (int)$settings['archived_documents_ttl_days'] : 1825;

        $db = \Config\Database::connect();
        
        $totalDeleted = [
            'messages_trashed' => 0,
            'messages_deleted' => 0,
            'logs_deleted' => 0,
            'archived_docs_deleted' => 0,
            'soft_deleted_docs_purged' => 0
        ];
        
        $detailedLogs = [];

        try {
            $db->transStart();

            // A. Move Old Messages to Trash (1 Year default)
            if ($messagesTtlDays > 0) {
                $db->query("
                    UPDATE messages 
                    SET deleted_by_sender_at = NOW(), deleted_by_recipient_at = NOW() 
                    WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                    AND (deleted_by_sender_at IS NULL OR deleted_by_recipient_at IS NULL)
                ", [$messagesTtlDays]);
                $totalDeleted['messages_trashed'] = $db->affectedRows();
            }

            // B. Permanently delete Trashed Messages older than trashTtlDays
            if ($trashTtlDays > 0) {
                $db->query("
                    DELETE FROM messages 
                    WHERE (deleted_by_sender_at IS NOT NULL AND deleted_by_sender_at < DATE_SUB(NOW(), INTERVAL ? DAY))
                       OR (deleted_by_recipient_at IS NOT NULL AND deleted_by_recipient_at < DATE_SUB(NOW(), INTERVAL ? DAY))
                ", [$trashTtlDays, $trashTtlDays]);
                $totalDeleted['messages_deleted'] = $db->affectedRows();
            }

            // C. Delete old Activity Logs (Split into Operational and Main)
            if ($logsTtlDays > 0) {
                $operationalActions = ['Login', 'Logout', 'Register User', 'Suspend User', 'Restore User', 'Delete User'];
                
                // C1. Delete Operational Logs older than 90 days
                $db->table('activity_logs')
                   ->whereIn('action', $operationalActions)
                   ->where('created_at <', date('Y-m-d H:i:s', strtotime('-90 days')))
                   ->delete();
                $opDeleted = $db->affectedRows();

                // C2. Delete Main Logs older than the configured TTL (usually 1 year)
                $db->table('activity_logs')
                   ->whereNotIn('action', $operationalActions)
                   ->where('created_at <', date('Y-m-d H:i:s', strtotime("-{$logsTtlDays} days")))
                   ->delete();
                $mainDeleted = $db->affectedRows();
                
                $totalDeleted['logs_deleted'] = $opDeleted + $mainDeleted;
            }

            // D. Permanently delete Soft-Deleted documents (Trashbin)
            if ($trashTtlDays > 0) {
                // Activity Designs
                $oldADs = $db->query("
                    SELECT activity_title FROM activity_design 
                    WHERE deleted_at IS NOT NULL AND deleted_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$trashTtlDays])->getResultArray();
                
                foreach ($oldADs as $ad) {
                    $detailedLogs[] = "Automatically permanently deleted Activity Design: " . ($ad['activity_title'] ?? 'Untitled');
                }

                $db->query("
                    DELETE FROM activity_design 
                    WHERE deleted_at IS NOT NULL AND deleted_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$trashTtlDays]);
                $totalDeleted['soft_deleted_docs_purged'] += $db->affectedRows();
                
                // Accomplishment Reports
                $oldARs = $db->query("
                    SELECT activity_title FROM accomplishment_report 
                    WHERE deleted_at IS NOT NULL AND deleted_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$trashTtlDays])->getResultArray();
                
                foreach ($oldARs as $ar) {
                    $detailedLogs[] = "Automatically permanently deleted Accomplishment Report: " . ($ar['activity_title'] ?? 'Untitled');
                }

                $db->query("
                    DELETE FROM accomplishment_report 
                    WHERE deleted_at IS NOT NULL AND deleted_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$trashTtlDays]);
                $totalDeleted['soft_deleted_docs_purged'] += $db->affectedRows();
            }

            // E. Delete old Archived Documents
            if ($archivesTtlDays > 0) {
                // Activity Designs marked as archived
                $oldArchivedADs = $db->query("
                    SELECT activity_title FROM activity_design 
                    WHERE is_archived = 1 AND archived_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$archivesTtlDays])->getResultArray();
                
                foreach ($oldArchivedADs as $ad) {
                    $detailedLogs[] = "Automatically deleted Archived Activity Design: " . ($ad['activity_title'] ?? 'Untitled');
                }

                $db->query("
                    DELETE FROM activity_design 
                    WHERE is_archived = 1 AND archived_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$archivesTtlDays]);
                $totalDeleted['archived_docs_deleted'] += $db->affectedRows();
                
                // Accomplishment Reports marked as archived
                $oldArchivedARs = $db->query("
                    SELECT activity_title FROM accomplishment_report 
                    WHERE is_archived = 1 AND archived_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$archivesTtlDays])->getResultArray();
                
                foreach ($oldArchivedARs as $ar) {
                    $detailedLogs[] = "Automatically deleted Archived Accomplishment Report: " . ($ar['activity_title'] ?? 'Untitled');
                }

                $db->query("
                    DELETE FROM accomplishment_report 
                    WHERE is_archived = 1 AND archived_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$archivesTtlDays]);
                $totalDeleted['archived_docs_deleted'] += $db->affectedRows();

                // Archived Annual Reports table
                $oldAnnual = $db->query("
                    SELECT fiscal_year FROM archived_annual_reports 
                    WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$archivesTtlDays])->getResultArray();
                
                foreach ($oldAnnual as $ann) {
                    $detailedLogs[] = "Automatically deleted Archived Annual Report: FY " . ($ann['fiscal_year'] ?? 'Unknown');
                }

                $db->query("
                    DELETE FROM archived_annual_reports 
                    WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
                ", [$archivesTtlDays]);
                $totalDeleted['archived_docs_deleted'] += $db->affectedRows();
            }

            $db->transComplete();

            if ($db->transStatus() !== false) {
                // Update last run time
                $settingModel->saveSetting('last_cleanup_run', date('Y-m-d H:i:s'), 0);
                
                // Log the action if something was actually deleted
                if (array_sum($totalDeleted) > 0) {
                    $logMsg = sprintf(
                        "System Cleanup: Trashed %d msgs, Purged %d msgs, %d logs, %d soft-deleted docs, %d archived docs.",
                        $totalDeleted['messages_trashed'],
                        $totalDeleted['messages_deleted'],
                        $totalDeleted['logs_deleted'],
                        $totalDeleted['soft_deleted_docs_purged'],
                        $totalDeleted['archived_docs_deleted']
                    );
                    // Fetch first admin user to attach the log to
                    $admin = $db->table('users')
                        ->groupStart()
                            ->where('role', 'Admin')
                            ->orWhere('role', 'admin')
                        ->groupEnd()
                        ->get()->getRowArray();
                        
                    if (!$admin) {
                        $admin = $db->table('users')->get()->getRowArray(); // fallback to any user
                    }
                    $adminId = $admin ? $admin['id'] : null;
                    
                    if ($adminId) {
                        try {
                            // Log the summary
                            ActivityLogModel::log($adminId, 'System Cleanup', $logMsg);
                            
                            // Log the individual document deletions
                            foreach ($detailedLogs as $detailLog) {
                                ActivityLogModel::log($adminId, 'System Cleanup', $detailLog);
                            }
                        } catch (\Exception $e) {
                            log_message('error', 'Failed to log System Cleanup: ' . $e->getMessage());
                        }
                    }
                }
                
                return true;
            }

        } catch (DatabaseException $e) {
            log_message('error', 'Data Retention Cleanup Failed: ' . $e->getMessage());
            return false;
        }

        return false;
    }
}
