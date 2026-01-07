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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('subdomain_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('subdomain_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['subdomain_id']);
            $table->dropIndex(['subdomain_id']);
            $table->dropColumn('subdomain_id');
        });
    }
};
