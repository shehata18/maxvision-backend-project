<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
        ]);
    }
}
