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
        // Indexes for appointments table
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('appointment_date');
            $table->index('status');
            $table->index(['appointment_date', 'status']);
            $table->index('created_by');
        });

        // Indexes for patients table
        Schema::table('patients', function (Blueprint $table) {
            $table->index('email');
            $table->index('phone');
            $table->index('created_at');
        });

        // Indexes for doctor_availabilities table
        Schema::table('doctor_availabilities', function (Blueprint $table) {
            $table->index('doctor_id');
            $table->index('type');
            $table->index('day_of_week');
            $table->index('specific_date');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['patient_id']);
            $table->dropIndex(['doctor_id']);
            $table->dropIndex(['appointment_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['appointment_date', 'status']);
            $table->dropIndex(['created_by']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('doctor_availabilities', function (Blueprint $table) {
            $table->dropIndex(['doctor_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['day_of_week']);
            $table->dropIndex(['specific_date']);
            $table->dropIndex(['start_date', 'end_date']);
        });
    }
};
