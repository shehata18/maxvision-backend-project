<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RestoreSeedData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'maxvision:restore-seed-data
                            {file : Path to the backup SQL file (relative to database/backups/)}';

    /**
     * The console command description.
     */
    protected $description = 'Restore seeded data from a SQL dump backup';

    public function handle(): int
    {
        $file = $this->argument('file');
        $filepath = database_path("backups/{$file}");

        if (!File::exists($filepath)) {
            $this->error("Backup file not found: {$filepath}");

            // List available backups
            $backups = File::glob(database_path('backups/seed-data-*'));
            if (!empty($backups)) {
                $this->info('Available backups:');
                foreach ($backups as $backup) {
                    $this->line('  - ' . basename($backup));
                }
            }

            return self::FAILURE;
        }

        // Production safety check
        if (app()->environment('production')) {
            if (!$this->confirm('⚠️  You are running in PRODUCTION. This will OVERWRITE existing data. Continue?')) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }
        }

        if (!$this->confirm("Restore from: {$file}? This will truncate affected tables.")) {
            $this->info('Aborted.');
            return self::SUCCESS;
        }

        $this->info('Restoring seed data...');

        // Read the file (handle gzip)
        if (str_ends_with($filepath, '.gz')) {
            $sql = gzdecode(File::get($filepath));
        } else {
            $sql = File::get($filepath);
        }

        // Execute statements
        $statements = array_filter(
            array_map('trim', explode(";\n", $sql)),
            fn ($s) => !empty($s) && !str_starts_with($s, '--')
        );

        $bar = $this->output->createProgressBar(count($statements));

        DB::beginTransaction();
        try {
            foreach ($statements as $statement) {
                if (!empty(trim($statement))) {
                    DB::unprepared($statement . ';');
                }
                $bar->advance();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $bar->finish();
            $this->newLine();
            $this->error("Restore failed: {$e->getMessage()}");
            return self::FAILURE;
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Seed data restored successfully!');

        return self::SUCCESS;
    }
}
