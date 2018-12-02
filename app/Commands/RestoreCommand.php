<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RestoreCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'restore {--snapshot= : provide of name snapshot}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Restore MySql Backup';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $snapshot    = $this->option('snapshot');
        $mysql_path  = config('mysql.mysql_path');
        $db_host     = config('mysql.db_host');
        $db_username = config('mysql.db_username');
        $db_password = config('mysql.db_password');

        if (!$snapshot) {
            $this->error("snapshot option is required.");
        }

        try
        {
            $database = explode('_', $snapshot)[0];
            $this->info('Restored database: ' . $database);
            $file    = storage_path($snapshot);
            $process = new Process($mysql_path . 'mysql -h' . $db_host . ' -u' . $db_username . ' -p' . $db_password . ' ' . $database . ' ' . $database . ' < ' . $file);
            $process->run();

            if ($process->isSuccessful()) {
                $this->info('Restored snapshot: ' . $snapshot);
            } else {
                throw new ProcessFailedException($process);
            }
        } catch (\Exception $e) {
            $this->info('File Not Found: ' . $e->getMessage());
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
