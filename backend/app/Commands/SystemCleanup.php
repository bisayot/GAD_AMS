<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\DataRetentionService;

class SystemCleanup extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'System';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'system:cleanup';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Runs the automated data retention cleanup to purge old messages, logs, and documents based on TTL settings.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'system:cleanup [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '-f' => 'Force run even if it was run recently',
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Starting System Cleanup...', 'cyan');

        $force = array_key_exists('f', $params);

        $result = DataRetentionService::runCleanup($force);

        if ($result) {
            CLI::write('Cleanup completed successfully.', 'green');
        } else {
            CLI::write('Cleanup skipped or failed. (Already run in the last 24h? Use -f to force).', 'yellow');
        }
    }
}
