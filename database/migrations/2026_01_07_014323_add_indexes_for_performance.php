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
        // Add indexes to subdomains table
        Schema::table('subdomains', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('created_at');
        });

        // Add indexes to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['status', 'end_date']);
            $table->index(['status', 'last_payment_date']);
            $table->index('subdomain_id');
            $table->index('created_at');
        });

        // Add indexes to registration_links table
        Schema::table('registration_links', function (Blueprint $table) {
            $table->index(['subdomain_id', 'is_active']);
            $table->index('is_active');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['status', 'end_date']);
            $table->dropIndex(['status', 'last_payment_date']);
            $table->dropIndex(['subdomain_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('registration_links', function (Blueprint $table) {
            $table->dropIndex(['subdomain_id', 'is_active']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['expires_at']);
        });
    }
};
