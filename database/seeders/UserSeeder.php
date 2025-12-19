<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin account
        User::create([
            'name' => 'Admin',
            'email' => 'admin@dental.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '123-456-7899',
            'is_active' => true,
        ]);

        // Create a doctor
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@dental.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '123-456-7890',
            'is_active' => true,
        ]);

        // Create a staff member
        User::create([
            'name' => 'Jane Doe',
            'email' => 'staff@dental.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '123-456-7891',
            'is_active' => true,
        ]);
    }
}
