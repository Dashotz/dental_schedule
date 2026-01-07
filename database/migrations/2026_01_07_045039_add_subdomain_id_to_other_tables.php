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
        // Treatment Plans
        Schema::table('treatment_plans', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });

        // Quotes
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });

        // Invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });

        // Payments
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });

        // Teeth Records
        Schema::table('teeth_records', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });

        // Treatments
        Schema::table('treatments', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });

        // Doctor Availabilities
        Schema::table('doctor_availabilities', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });

        // Blocked Slots (if table exists)
        if (Schema::hasTable('blocked_slots')) {
            Schema::table('blocked_slots', function (Blueprint $table) {
                $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                $table->index('subdomain_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_plans', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });

        Schema::table('teeth_records', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });

        Schema::table('doctor_availabilities', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });

        if (Schema::hasTable('blocked_slots')) {
            Schema::table('blocked_slots', function (Blueprint $table) {
                $table->dropForeign(['subdomain_id']);
                $table->dropIndex(['subdomain_id']);
                $table->dropColumn('subdomain_id');
            });
        }
    }
};
