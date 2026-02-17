<?php

namespace Database\Seeders\Development;

use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CaseStudySeeder;
use Database\Seeders\CompanyInfoSeeder;
use Database\Seeders\ContactSubmissionSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\SolutionSeeder;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Seed all data for development environments.
     *
     * This includes everything:
     * - Admin user account
     * - All 8 products with features, specs, and applications
     * - All 5 solutions with benefits, specs, and recommended products
     * - All 5 case studies with metrics, specs, and product associations
     * - Company information (milestones, team, certs, partners, stats)
     * - Site settings (contact info, social media, hero, footer)
     * - 23 sample contact submissions with varied statuses
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ProductSeeder::class,       // Must run first (products needed for relationships)
            SolutionSeeder::class,      // Depends on products
            CaseStudySeeder::class,     // Depends on products
            CompanyInfoSeeder::class,
            SettingsSeeder::class,
            ContactSubmissionSeeder::class,
        ]);
    }
}
