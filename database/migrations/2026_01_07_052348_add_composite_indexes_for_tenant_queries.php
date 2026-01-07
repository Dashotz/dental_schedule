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
        // Composite indexes for appointments table (most common queries)
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['subdomain_id', 'appointment_date', 'status'], 'idx_appointments_subdomain_date_status');
            $table->index(['subdomain_id', 'doctor_id', 'appointment_date'], 'idx_appointments_subdomain_doctor_date');
            $table->index(['subdomain_id', 'patient_id'], 'idx_appointments_subdomain_patient');
            $table->index(['subdomain_id', 'status'], 'idx_appointments_subdomain_status');
        });

        // Composite indexes for patients table
        Schema::table('patients', function (Blueprint $table) {
            $table->index(['subdomain_id', 'email'], 'idx_patients_subdomain_email');
            $table->index(['subdomain_id', 'phone'], 'idx_patients_subdomain_phone');
            $table->index(['subdomain_id', 'created_at'], 'idx_patients_subdomain_created');
        });

        // Composite indexes for users (doctors) table
        Schema::table('users', function (Blueprint $table) {
            $table->index(['subdomain_id', 'is_active'], 'idx_users_subdomain_active');
        });

        // Composite indexes for doctor_availabilities table
        Schema::table('doctor_availabilities', function (Blueprint $table) {
            $table->index(['subdomain_id', 'doctor_id', 'type'], 'idx_availabilities_subdomain_doctor_type');
            $table->index(['subdomain_id', 'doctor_id', 'day_of_week'], 'idx_availabilities_subdomain_doctor_day');
        });

        // Composite indexes for blocked_slots table
        if (Schema::hasTable('blocked_slots')) {
            Schema::table('blocked_slots', function (Blueprint $table) {
                $table->index(['subdomain_id', 'doctor_id', 'slot_date'], 'idx_blocked_slots_subdomain_doctor_date');
            });
        }

        // Composite indexes for teeth_records table
        Schema::table('teeth_records', function (Blueprint $table) {
            $table->index(['subdomain_id', 'patient_id'], 'idx_teeth_records_subdomain_patient');
        });

        // Composite indexes for treatments table
        Schema::table('treatments', function (Blueprint $table) {
            $table->index(['subdomain_id', 'patient_id'], 'idx_treatments_subdomain_patient');
            $table->index(['subdomain_id', 'appointment_id'], 'idx_treatments_subdomain_appointment');
        });

        // Composite indexes for invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['subdomain_id', 'patient_id'], 'idx_invoices_subdomain_patient');
            $table->index(['subdomain_id', 'status'], 'idx_invoices_subdomain_status');
        });

        // Composite indexes for payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['subdomain_id', 'patient_id'], 'idx_payments_subdomain_patient');
            $table->index(['subdomain_id', 'invoice_id'], 'idx_payments_subdomain_invoice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('idx_appointments_subdomain_date_status');
            $table->dropIndex('idx_appointments_subdomain_doctor_date');
            $table->dropIndex('idx_appointments_subdomain_patient');
            $table->dropIndex('idx_appointments_subdomain_status');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex('idx_patients_subdomain_email');
            $table->dropIndex('idx_patients_subdomain_phone');
            $table->dropIndex('idx_patients_subdomain_created');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_subdomain_active');
        });

        Schema::table('doctor_availabilities', function (Blueprint $table) {
            $table->dropIndex('idx_availabilities_subdomain_doctor_type');
            $table->dropIndex('idx_availabilities_subdomain_doctor_day');
        });

        if (Schema::hasTable('blocked_slots')) {
            Schema::table('blocked_slots', function (Blueprint $table) {
                $table->dropIndex('idx_blocked_slots_subdomain_doctor_date');
            });
        }

        Schema::table('teeth_records', function (Blueprint $table) {
            $table->dropIndex('idx_teeth_records_subdomain_patient');
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->dropIndex('idx_treatments_subdomain_patient');
            $table->dropIndex('idx_treatments_subdomain_appointment');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_subdomain_patient');
            $table->dropIndex('idx_invoices_subdomain_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_subdomain_patient');
            $table->dropIndex('idx_payments_subdomain_invoice');
        });
    }
};
