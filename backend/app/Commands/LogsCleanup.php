<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class LogsCleanup extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';
    protected $name = 'logs:cleanup';
    protected $description = 'Cleans up old activity logs (90 days for operational, 1 year for main logs).';
    protected $usage = 'logs:cleanup';
    protected $arguments = [];
    protected $options = [];

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('activity_logs');
        
        $operationalActions = ['Login', 'Logout', 'Register User', 'Suspend User', 'Restore User', 'Delete User'];
        
        // 1. Delete Operational Logs older than 90 days
        $ninetyDaysAgo = date('Y-m-d H:i:s', strtotime('-90 days'));
        
        CLI::write("Deleting Operational Logs older than: $ninetyDaysAgo", 'yellow');
        
        $builder->whereIn('action', $operationalActions)
                ->where('created_at <', $ninetyDaysAgo)
                ->delete();
                
        $opDeleted = $db->affectedRows();
        CLI::write("Deleted $opDeleted operational logs.", 'green');
        
        // 2. Delete Main Logs older than 1 year (365 days)
        $oneYearAgo = date('Y-m-d H:i:s', strtotime('-1 year'));
        
        CLI::write("Deleting Main Logs older than: $oneYearAgo", 'yellow');
        
        $builder->whereNotIn('action', $operationalActions)
                ->where('created_at <', $oneYearAgo)
                ->delete();
                
        $mainDeleted = $db->affectedRows();
        CLI::write("Deleted $mainDeleted main logs.", 'green');
        
        CLI::write("Logs cleanup completed.", 'cyan');
    }
}
