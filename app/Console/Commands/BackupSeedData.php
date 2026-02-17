<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackupSeedData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'maxvision:backup-seed-data
                            {--compress : Compress the backup with gzip}';

    /**
     * The console command description.
     */
    protected $description = 'Export all seeded tables to a SQL dump file for backup';

    /**
     * Tables to back up, in dependency order.
     */
    private array $tables = [
        'users',
        'products',
        'product_features',
        'product_applications',
        'product_specifications',
        'solutions',
        'solution_benefits',
        'solution_specs',
        'solution_product',
        'case_studies',
        'case_study_metrics',
        'case_study_specs',
        'case_study_product',
        'company_info',
        'settings',
        'contact_submissions',
    ];

    public function handle(): int
    {
        $backupDir = database_path('backups');
        File::ensureDirectoryExists($backupDir);

        $timestamp = now()->format('Y-m-d_His');
        $filename = "seed-data-{$timestamp}.sql";
        $filepath = "{$backupDir}/{$filename}";

        $this->info('Backing up seeded data...');
        $this->newLine();

        $sql = "-- MaxVision Seed Data Backup\n";
        $sql .= "-- Generated: " . now()->toDateTimeString() . "\n";
        $sql .= "-- Environment: " . app()->environment() . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        $bar = $this->output->createProgressBar(count($this->tables));

        foreach ($this->tables as $table) {
            try {
                $rows = DB::table($table)->get();

                if ($rows->isEmpty()) {
                    $bar->advance();
                    continue;
                }

                $sql .= "-- Table: {$table} ({$rows->count()} rows)\n";
                $sql .= "TRUNCATE TABLE `{$table}`;\n";

                foreach ($rows as $row) {
                    $values = collect((array) $row)->map(function ($value) {
                        if (is_null($value)) {
                            return 'NULL';
                        }
                        return "'" . addslashes((string) $value) . "'";
                    })->implode(', ');

                    $columns = collect(array_keys((array) $row))->map(fn ($col) => "`{$col}`")->implode(', ');
                    $sql .= "INSERT INTO `{$table}` ({$columns}) VALUES ({$values});\n";
                }

                $sql .= "\n";
                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("  Skipping {$table}: {$e->getMessage()}");
                $bar->advance();
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        $bar->finish();
        $this->newLine(2);

        File::put($filepath, $sql);

        if ($this->option('compress')) {
            $gzFilepath = "{$filepath}.gz";
            $gz = gzopen($gzFilepath, 'w9');
            gzwrite($gz, $sql);
            gzclose($gz);
            File::delete($filepath);
            $filepath = $gzFilepath;
            $filename .= '.gz';
        }

        $size = number_format(File::size($filepath) / 1024, 1);
        $this->info("✅ Backup saved: database/backups/{$filename} ({$size} KB)");

        return self::SUCCESS;
    }
}
