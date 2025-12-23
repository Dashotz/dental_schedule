<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DoctorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'type',
        'day_of_week',
        'specific_date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'slot_duration',
        'is_available',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'specific_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'slot_duration' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    // Relationships
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Helper methods
    public function getTimeSlots(): array
    {
        $slots = [];
        // Parse time strings (format: HH:MM:SS or HH:MM)
        $startTime = $this->start_time;
        $endTime = $this->end_time;
        
        // Ensure time format includes seconds
        if (strlen($startTime) === 5) {
            $startTime .= ':00';
        }
        if (strlen($endTime) === 5) {
            $endTime .= ':00';
        }
        
        $start = Carbon::createFromFormat('H:i:s', $startTime);
        $end = Carbon::createFromFormat('H:i:s', $endTime);
        $duration = $this->slot_duration;

        $current = $start->copy();
        while ($current->copy()->addMinutes($duration)->lte($end)) {
            $slots[] = [
                'start' => $current->format('H:i'),
                'end' => $current->copy()->addMinutes($duration)->format('H:i'),
            ];
            $current->addMinutes($duration);
        }

        return $slots;
    }

    public function isDateInRange(Carbon $date): bool
    {
        if ($this->type === 'specific_date') {
            return $this->specific_date && $this->specific_date->isSameDay($date);
        }

        if ($this->type === 'date_range') {
            return $this->start_date && $this->end_date &&
                   $date->gte($this->start_date) && $date->lte($this->end_date);
        }

        if ($this->type === 'weekly') {
            return $this->day_of_week !== null && $date->dayOfWeek === $this->day_of_week;
        }

        return false;
    }
}
