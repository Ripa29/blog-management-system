<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\BlogSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\BlogCategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@blog.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'john@blog.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'jane@blog.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            BlogCategorySeeder::class,
            BlogSeeder::class,
        ]);
    }
}
