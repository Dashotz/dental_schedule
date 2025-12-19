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
        Schema::table('teeth_records', function (Blueprint $table) {
            // Drop columns that are not needed
            if (Schema::hasColumn('teeth_records', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('teeth_records', 'tooth_position')) {
                $table->dropColumn('tooth_position');
            }
            if (Schema::hasColumn('teeth_records', 'record_date')) {
                $table->dropColumn('record_date');
            }
            if (Schema::hasColumn('teeth_records', 'xray_image')) {
                $table->dropColumn('xray_image');
            }
            if (Schema::hasColumn('teeth_records', 'notes')) {
                $table->dropColumn('notes');
            }
            
            // Change condition from text to string
            if (Schema::hasColumn('teeth_records', 'condition')) {
                $table->string('condition')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teeth_records', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('tooth_position', ['upper_right', 'upper_left', 'lower_right', 'lower_left'])->nullable();
            $table->date('record_date');
            $table->string('xray_image')->nullable();
            $table->text('notes')->nullable();
            $table->text('condition')->nullable()->change();
        });
    }
};
