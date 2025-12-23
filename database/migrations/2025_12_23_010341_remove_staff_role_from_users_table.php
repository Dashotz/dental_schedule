<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing staff users to doctor role
        \DB::table('users')->where('role', 'staff')->update(['role' => 'doctor']);
        
        // Modify the enum column to remove 'staff' and change default
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'doctor') NOT NULL DEFAULT 'doctor'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore staff role
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'doctor', 'staff') NOT NULL DEFAULT 'staff'");
    }
};
