<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin account in Admin table
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@dental',
            'password' => Hash::make('Password1!'),
            'phone' => '123-456-7899',
            'is_active' => true,
        ]);

        // Create a doctor in Users table
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@dental',
            'password' => Hash::make('Password1!'),
            'phone' => '123-456-7890',
            'is_active' => true,
        ]);

        // Create a staff member (as User/Doctor for now, as roles effectively merged/removed)
        User::create([
            'name' => 'Jane Doe',
            'email' => 'staff@dental',
            'password' => Hash::make('Password1!'),
            'phone' => '123-456-7891',
            'is_active' => true,
        ]);
    }
}
