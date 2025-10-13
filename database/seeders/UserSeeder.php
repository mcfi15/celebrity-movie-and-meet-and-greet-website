<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@celebrityagency.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-123-4567',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-987-6543',
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}