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
            ['email' => 'smith@blog.com'],
            [
                'name' => 'Smith User',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@blog.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password'),
                'status' => 0,
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

        User::firstOrCreate(
            ['email' => 'mike@blog.com'],
            [
                'name' => 'Mike Johnson',
                'password' => Hash::make('password'),
                'status' => 0,
                'email_verified_at' => now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'opticodex@blog.com'],
            [
                'name' => 'Opticodex',
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
