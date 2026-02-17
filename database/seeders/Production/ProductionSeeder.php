<?php

namespace Database\Seeders\Production;

use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CompanyInfoSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed only essential data for production environments.
     *
     * This includes:
     * - Admin user account
     * - Company information (milestones, team, certs, partners, stats)
     * - Site settings (contact info, social media, hero, footer)
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CompanyInfoSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
