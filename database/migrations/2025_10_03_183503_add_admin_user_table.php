<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@blog.com',
            'password' => Hash::make('password'),
            'status' => 1,
            'email_verified_at' => now(),
        ]);

        $sampleUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@blog.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@blog.com',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@blog.com',
                'password' => Hash::make('password'),
                'status' => 0,
                'email_verified_at' => now(),
            ]
        ];

        foreach ($sampleUsers as $user) {
            User::create($user);
        }
    }

    public function down(): void
    {
        User::where('email', 'admin@blog.com')
            ->orWhere('email', 'john@blog.com')
            ->orWhere('email', 'jane@blog.com')
            ->orWhere('email', 'mike@blog.com')
            ->delete();
    }
};
