<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the admin user for the MaxVision CMS.
     *
     * NOTE: Change the password in production!
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@maxvision.com'],
            [
                'name' => 'MaxVision Admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
