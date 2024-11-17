<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database to the public directory';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Database configuration from .env
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');

        // File path and name with timestamp
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $filePath = public_path("backup_{$date}.sql");

        // Ensure mysqldump command handles the password securely
        $command = "mysqldump -h {$host} -u {$username} -p\"{$password}\" {$database} > \"{$filePath}\"";

        // Run the command
        $result = null;
        system($command, $result);

        // Check the result
        if ($result === 0) {
            $this->info("Database backup was successful. File saved to: {$filePath}");
        } else {
            $this->error("Database backup failed. Please check your configuration.");
        }

        return $result;
    }
}
