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
        Schema::create('blocked_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->date('slot_date'); // The date of the blocked slot
            $table->time('slot_start_time'); // Start time of the slot (e.g., 10:30)
            $table->time('slot_end_time'); // End time of the slot (e.g., 11:00)
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['doctor_id', 'slot_date']);
            $table->index(['slot_date', 'slot_start_time', 'slot_end_time']);
            
            // Prevent duplicate blocked slots
            $table->unique(['doctor_id', 'slot_date', 'slot_start_time', 'slot_end_time'], 'unique_blocked_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_slots');
    }
};

