<?php

namespace App\Commands;

use App\Libraries\ArDeadlineService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckArDeadlines extends BaseCommand
{
    protected $group       = 'Notifications';
    protected $name        = 'ar:check-deadlines';
    protected $description = 'Checks 15-working-day Accomplishment Report deadlines and sends milestone email/in-app notifications.';
    protected $usage       = 'ar:check-deadlines';

    public function run(array $params)
    {
        CLI::write('Checking Accomplishment Report deadlines...', 'cyan');

        try {
            $result = ArDeadlineService::checkAndDispatchDeadlines();
            CLI::write("Deadlines checked successfully.", 'green');
            CLI::write("Processed activities: {$result['processed']}", 'yellow');
            CLI::write("Notifications sent: {$result['sent']}", 'yellow');
        } catch (\Throwable $e) {
            CLI::error('Error checking AR deadlines: ' . $e->getMessage());
            log_message('error', 'Error in CheckArDeadlines command: ' . $e->getMessage());
        }
    }
}

