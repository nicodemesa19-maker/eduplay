<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@eduplay.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin@123!'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_approved' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
                'is_approved' => true,
            ]
        );

        $this->call(GamesTableSeeder::class);
    }
}
