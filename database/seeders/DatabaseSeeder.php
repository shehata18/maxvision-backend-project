<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Environment-aware seeding:
     * - Production:  Admin user, Settings, CompanyInfo only
     * - Staging:     All data except contact submissions
     * - Development: All data including sample submissions
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            // Production: only essential data
            $this->call([
                AdminUserSeeder::class,
                CompanyInfoSeeder::class,
                SettingsSeeder::class,
            ]);

            return;
        }

        // Development & Staging: full dataset
        $this->call([
            AdminUserSeeder::class,
            ProductSeeder::class,       // Must run first (products needed for relationships)
            SolutionSeeder::class,      // Depends on products
            CaseStudySeeder::class,     // Depends on products
            CompanyInfoSeeder::class,
            SettingsSeeder::class,
        ]);

        // Development only: sample contact submissions
        if (!app()->environment('staging')) {
            $this->call([
                ContactSubmissionSeeder::class,
            ]);
        }
    }
}
