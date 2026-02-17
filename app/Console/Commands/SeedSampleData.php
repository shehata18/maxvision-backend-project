<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedSampleData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'maxvision:seed-sample-data
                            {--fresh : Run migrate:fresh before seeding}
                            {--only= : Seed only a specific table (products, solutions, case-studies, company, settings, contacts)}';

    /**
     * The console command description.
     */
    protected $description = 'Seed the database with MaxVision sample data in the correct dependency order';

    /**
     * Map of --only values to seeder classes.
     */
    private array $seederMap = [
        'products'     => \Database\Seeders\ProductSeeder::class,
        'solutions'    => \Database\Seeders\SolutionSeeder::class,
        'case-studies' => \Database\Seeders\CaseStudySeeder::class,
        'company'      => \Database\Seeders\CompanyInfoSeeder::class,
        'settings'     => \Database\Seeders\SettingsSeeder::class,
        'contacts'     => \Database\Seeders\ContactSubmissionSeeder::class,
    ];

    public function handle(): int
    {
        // Production safety check
        if (app()->environment('production')) {
            if (!$this->confirm('⚠️  You are running in PRODUCTION. Are you sure you want to seed sample data?')) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }
        }

        // Fresh migration
        if ($this->option('fresh')) {
            $this->warn('Running migrate:fresh — all existing data will be lost...');
            if (!$this->confirm('Continue?')) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }

            $this->info('');
            $this->line('  <fg=yellow>▸</> Running migrate:fresh...');
            Artisan::call('migrate:fresh', [], $this->output);
            $this->newLine();
        }

        // Selective seeding
        $only = $this->option('only');
        if ($only) {
            if (!isset($this->seederMap[$only])) {
                $this->error("Unknown seeder: {$only}");
                $this->line('Available options: ' . implode(', ', array_keys($this->seederMap)));
                return self::FAILURE;
            }

            $this->info("Seeding: {$only}");
            $this->runSeeder($this->seederMap[$only], $only);
            $this->newLine();
            $this->info('✅ Done!');
            return self::SUCCESS;
        }

        // Full seeding in dependency order
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   MaxVision Database Seeder              ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->newLine();

        $seeders = [
            ['class' => \Database\Seeders\AdminUserSeeder::class,          'label' => 'Admin User',          'table' => 'users'],
            ['class' => \Database\Seeders\ProductSeeder::class,            'label' => 'Products',            'table' => 'products'],
            ['class' => \Database\Seeders\SolutionSeeder::class,           'label' => 'Solutions',           'table' => 'solutions'],
            ['class' => \Database\Seeders\CaseStudySeeder::class,          'label' => 'Case Studies',        'table' => 'case_studies'],
            ['class' => \Database\Seeders\CompanyInfoSeeder::class,        'label' => 'Company Info',        'table' => 'company_info'],
            ['class' => \Database\Seeders\SettingsSeeder::class,           'label' => 'Settings',            'table' => 'settings'],
            ['class' => \Database\Seeders\ContactSubmissionSeeder::class,  'label' => 'Contact Submissions', 'table' => 'contact_submissions'],
        ];

        $results = [];
        foreach ($seeders as $seeder) {
            $before = \DB::table($seeder['table'])->count();
            $this->runSeeder($seeder['class'], $seeder['label']);
            $after = \DB::table($seeder['table'])->count();
            $results[] = [
                'label' => $seeder['label'],
                'created' => $after - $before,
                'total' => $after,
            ];
        }

        // Summary
        $this->newLine();
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   Seeding Summary                        ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->newLine();

        $this->table(
            ['Seeder', 'New Records', 'Total'],
            collect($results)->map(fn ($r) => [$r['label'], $r['created'], $r['total']])->toArray()
        );

        $this->newLine();
        $this->info('✅ All seeders completed successfully!');

        return self::SUCCESS;
    }

    /**
     * Run a single seeder class and display progress.
     */
    private function runSeeder(string $class, string $label): void
    {
        $this->line("  <fg=cyan>▸</> Seeding {$label}...");

        try {
            Artisan::call('db:seed', ['--class' => $class, '--force' => true], $this->output);
            $this->line("  <fg=green>✔</> {$label} seeded successfully");
        } catch (\Exception $e) {
            $this->error("  ✘ Failed to seed {$label}: {$e->getMessage()}");
        }
    }
}
