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
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['weekly', 'specific_date', 'date_range'])->default('weekly');
            $table->integer('day_of_week')->nullable(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            $table->date('specific_date')->nullable(); // For specific date overrides
            $table->date('start_date')->nullable(); // For date ranges
            $table->date('end_date')->nullable(); // For date ranges
            $table->time('start_time'); // e.g., 09:00
            $table->time('end_time'); // e.g., 17:00
            $table->integer('slot_duration')->default(30); // Duration in minutes (15, 30, 45, 60)
            $table->boolean('is_available')->default(true); // false = blocked/unavailable
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['doctor_id', 'type', 'day_of_week']);
            $table->index(['doctor_id', 'specific_date']);
            $table->index(['doctor_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};
