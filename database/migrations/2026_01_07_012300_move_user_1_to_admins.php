<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get user with ID 1
        $user = DB::table('users')->where('id', 1)->first();
        
        if ($user) {
            // Check if admin already exists with this email
            $adminExists = DB::table('admins')->where('email', $user->email)->exists();
            
            if (!$adminExists) {
                // Insert into admins table
                DB::table('admins')->insert([
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'password' => $user->password,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'photo' => $user->photo,
                    'is_active' => $user->is_active,
                    'remember_token' => $user->remember_token,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
                
                // Delete from users table
                DB::table('users')->where('id', 1)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get admin with the email from user ID 1 (if we can find it)
        // This is a simplified rollback - in practice you'd need to track which admin was user 1
        $admin = DB::table('admins')->where('id', 1)->orWhere('email', function($query) {
            // Try to find admin that might have been user 1
        })->first();
        
        if ($admin) {
            // Check if user already exists
            $userExists = DB::table('users')->where('email', $admin->email)->exists();
            
            if (!$userExists) {
                // Insert back into users table
                DB::table('users')->insert([
                    'id' => 1,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'email_verified_at' => $admin->email_verified_at,
                    'password' => $admin->password,
                    'phone' => $admin->phone,
                    'address' => $admin->address,
                    'photo' => $admin->photo,
                    'is_active' => $admin->is_active,
                    'remember_token' => $admin->remember_token,
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at,
                ]);
                
                // Delete from admins table
                DB::table('admins')->where('email', $admin->email)->delete();
            }
        }
    }
};

