<?php

namespace App\Commands;

use App\Controllers\BackupController;
use App\Libraries\NotificationService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use RuntimeException;

class BackupsRun extends BaseCommand
{
    protected $group = 'Backups';
    protected $name = 'backups:run';
    protected $description = 'Creates the daily Google Drive backup and cleans expired backups.';
    protected $usage = 'backups:run';

    public function run(array $params)
    {
        $lockPath = WRITEPATH . 'backup-automation.lock';
        $handle = fopen($lockPath, 'c');
        if ($handle === false) {
            throw new RuntimeException('Unable to open the backup automation lock.');
        }

        $deadline = time() + (30 * 60);
        while (!flock($handle, LOCK_EX | LOCK_NB)) {
            if (time() >= $deadline) {
                fclose($handle);
                $this->notifyFailure('The scheduled backup could not start because another backup ran for more than 30 minutes.');
                throw new RuntimeException('Timed out waiting for another backup run.');
            }
            sleep(5);
        }

        try {
            CLI::write('Creating scheduled backup...', 'cyan');
            $result = (new BackupController())->runScheduledBackup();
            CLI::write('Scheduled backup and cleanup completed.', 'green');
            CLI::write(json_encode($result, JSON_THROW_ON_ERROR), 'green');
        } catch (\Throwable $exception) {
            log_message('error', 'Scheduled backup failed: {message}', ['message' => $exception->getMessage()]);
            $this->notifyFailure($exception->getMessage());
            CLI::error('Scheduled backup failed: ' . $exception->getMessage());
            throw $exception;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    private function notifyFailure(string $reason): void
    {
        $recipients = Database::connect()->table('users')
            ->select('email')
            ->where('deleted_at', null)
            ->groupStart()
                ->whereIn('role', ['admin', 'gad_staff'])
                ->orWhere('profile_role', 'Director')
            ->groupEnd()
            ->where('email IS NOT NULL', null, false)
            ->where('email !=', '')
            ->get()
            ->getResultArray();

        $emails = array_values(array_unique(array_filter(array_column($recipients, 'email'))));
        if ($emails) {
            NotificationService::sendMassEmailBcc(
                $emails,
                'GAD AMS automatic backup failed',
                'The scheduled backup process failed. Cleanup was not considered successful and will be retried on the next run.' . PHP_EOL . PHP_EOL . $reason,
                '/admin/backups'
            );
        }
    }
}
