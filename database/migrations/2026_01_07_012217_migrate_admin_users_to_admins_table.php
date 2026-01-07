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
        // Check if role column exists before querying
        $hasRoleColumn = Schema::hasColumn('users', 'role');
        
        if ($hasRoleColumn) {
        // Migrate admin users from users table to admins table
        $adminUsers = DB::table('users')->where('role', 'admin')->get();
        
        foreach ($adminUsers as $admin) {
                // Check if admin already exists to avoid duplicates
                $exists = DB::table('admins')->where('email', $admin->email)->exists();
                if (!$exists) {
            DB::table('admins')->insert([
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
                }
            }
        }
        
        // Note: If role column doesn't exist, it means admins were already separated
        // or the migration has already been run
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move admins back to users table
        $admins = DB::table('admins')->get();
        
        foreach ($admins as $admin) {
            DB::table('users')->insert([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'email_verified_at' => $admin->email_verified_at,
                'password' => $admin->password,
                'role' => 'admin',
                'phone' => $admin->phone,
                'address' => $admin->address,
                'photo' => $admin->photo,
                'is_active' => $admin->is_active,
                'remember_token' => $admin->remember_token,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ]);
        }
    }
};
