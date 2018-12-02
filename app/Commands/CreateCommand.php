<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CreateCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create {--database= : provide of name database}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create MySql Backup';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $database = $this->option('database');
        if ($database) {
            $this->backup($database);
        } else {
            $databases = explode(',', config('mysql.db_databases'));
            foreach ($databases as $database) {
                if ($database) {
                    $this->backup($database);
                }
            }
        }
    }

    protected function backup($database)
    {
        $mysql_path         = config('mysql.mysql_path');
        $db_host            = config('mysql.db_host');
        $db_username        = config('mysql.db_username');
        $db_password        = config('mysql.db_password');
        $backup_days        = config('mysql.backup_days');
        $current_timestamp  = time() - (24 * 3600 * $backup_days);
        $temp_file_location = '/tmp/' . $database . '_' . date('Y-m-d_Hi') . time() . '.sql';
        $target_file_path   = $database . '_' . date('Y-m-d_Hi') . time() . '.sql';
        $process            = new Process($mysql_path . 'mysqldump -h' . $db_host . ' -u' . $db_username . ' -p' . $db_password . ' ' . $database . ' > ' . $temp_file_location);
        $process->run();

        try
        {
            if ($process->isSuccessful()) {
                Storage::put($target_file_path, file_get_contents($temp_file_location));
                $files = Storage::files();
                foreach ($files as $file) {
                    if (Storage::lastModified($file) < $current_timestamp) {
                        Storage::delete($file);
                        $this->info("File: {$file} deleted.");
                    }
                }
                $this->info("Congratulation, database {$database} backup down!");
            } else {
                throw new ProcessFailedException($process);
            }

            unlink($temp_file_location);
        } catch (\Exception $e) {
            $this->info($e->getMessage());
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
        $schedule->command(static::class)->daily();
    }
}
