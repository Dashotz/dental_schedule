<?php

namespace App\Services\Availability;

use App\Models\DoctorAvailability;
use App\Models\Subdomain;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Create a new availability schedule
     */
    public function create(array $data, User $doctor, ?Subdomain $subdomain = null): DoctorAvailability
    {
        $subdomain = $subdomain ?? TenantContext::ensureSubdomain();

        $availability = DoctorAvailability::create([
            'subdomain_id' => $subdomain->id,
            'doctor_id' => $doctor->id,
            'type' => $data['type'],
            'day_of_week' => $data['day_of_week'] ?? null,
            'specific_date' => $data['specific_date'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'slot_duration' => $data['slot_duration'],
            'is_available' => $data['is_available'] ?? true,
            'notes' => $data['notes'] ?? null,
        ]);

        // Audit log
        Log::info('Availability schedule created', [
            'availability_id' => $availability->id,
            'doctor_id' => $doctor->id,
            'subdomain_id' => $subdomain->id,
            'type' => $data['type'],
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return $availability;
    }

    /**
     * Update an existing availability schedule
     */
    public function update(DoctorAvailability $availability, array $data): DoctorAvailability
    {
        TenantContext::verifyOwnership($availability, 'Availability');

        $availability->update([
            'type' => $data['type'] ?? $availability->type,
            'day_of_week' => $data['day_of_week'] ?? $availability->day_of_week,
            'specific_date' => $data['specific_date'] ?? $availability->specific_date,
            'start_date' => $data['start_date'] ?? $availability->start_date,
            'end_date' => $data['end_date'] ?? $availability->end_date,
            'start_time' => $data['start_time'] ?? $availability->start_time,
            'end_time' => $data['end_time'] ?? $availability->end_time,
            'slot_duration' => $data['slot_duration'] ?? $availability->slot_duration,
            'is_available' => $data['is_available'] ?? $availability->is_available,
            'notes' => $data['notes'] ?? $availability->notes,
        ]);

        // Audit log
        Log::info('Availability schedule updated', [
            'availability_id' => $availability->id,
            'doctor_id' => $availability->doctor_id,
            'subdomain_id' => $availability->subdomain_id,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return $availability->fresh();
    }

    /**
     * Delete an availability schedule
     */
    public function delete(DoctorAvailability $availability): bool
    {
        TenantContext::verifyOwnership($availability, 'Availability');

        $availabilityId = $availability->id;
        $doctorId = $availability->doctor_id;
        $subdomainId = $availability->subdomain_id;

        $deleted = $availability->delete();

        if ($deleted) {
            // Audit log
            Log::info('Availability schedule deleted', [
                'availability_id' => $availabilityId,
                'doctor_id' => $doctorId,
                'subdomain_id' => $subdomainId,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        }

        return $deleted;
    }

    /**
     * Block a specific day
     */
    public function blockDay(User $doctor, Carbon $date, ?Subdomain $subdomain = null): DoctorAvailability
    {
        $subdomain = $subdomain ?? TenantContext::ensureSubdomain();

        // Check if day is already blocked
        $existing = DoctorAvailability::where('subdomain_id', $subdomain->id)
            ->where('doctor_id', $doctor->id)
            ->where('type', 'specific_date')
            ->where('specific_date', $date->format('Y-m-d'))
            ->where('is_available', false)
            ->first();

        if ($existing) {
            return $existing;
        }

        return $this->create([
            'type' => 'specific_date',
            'specific_date' => $date->format('Y-m-d'),
            'start_time' => '00:00',
            'end_time' => '23:59',
            'slot_duration' => 30,
            'is_available' => false,
            'notes' => 'Blocked day',
        ], $doctor, $subdomain);
    }

    /**
     * Block specific hours on a date
     */
    public function blockHours(User $doctor, Carbon $date, string $startTime, string $endTime, ?Subdomain $subdomain = null): int
    {
        $subdomain = $subdomain ?? TenantContext::ensureSubdomain();
        $slotDate = $date->format('Y-m-d');
        $blockedCount = 0;

        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);
        $current = $start->copy();

        while ($current->copy()->addMinutes(30)->lte($end)) {
            $slotStart = $current->format('H:i');
            $slotEnd = $current->copy()->addMinutes(30)->format('H:i');

            // Check if slot is already blocked
            $exists = DB::table('blocked_slots')
                ->where('subdomain_id', $subdomain->id)
                ->where('doctor_id', $doctor->id)
                ->where('slot_date', $slotDate)
                ->where('slot_start_time', $slotStart)
                ->where('slot_end_time', $slotEnd)
                ->exists();

            if (!$exists) {
                DB::table('blocked_slots')->insert([
                    'subdomain_id' => $subdomain->id,
                    'doctor_id' => $doctor->id,
                    'slot_date' => $slotDate,
                    'slot_start_time' => $slotStart,
                    'slot_end_time' => $slotEnd,
                    'notes' => 'Blocked hours',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $blockedCount++;
            }

            $current->addMinutes(30);
        }

        // Audit log
        if ($blockedCount > 0) {
            Log::info('Hours blocked', [
                'doctor_id' => $doctor->id,
                'subdomain_id' => $subdomain->id,
                'date' => $slotDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'slots_blocked' => $blockedCount,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        }

        return $blockedCount;
    }

    /**
     * Unblock slots for a date
     */
    public function unblockDate(User $doctor, Carbon $date, ?Subdomain $subdomain = null): int
    {
        $subdomain = $subdomain ?? TenantContext::ensureSubdomain();

        // Delete blocked slots
        $deletedCount = DB::table('blocked_slots')
            ->where('subdomain_id', $subdomain->id)
            ->where('doctor_id', $doctor->id)
            ->where('slot_date', $date->format('Y-m-d'))
            ->delete();

        // Delete availability entries for the date
        DoctorAvailability::where('subdomain_id', $subdomain->id)
            ->where('doctor_id', $doctor->id)
            ->where('is_available', false)
            ->where(function($query) use ($date) {
                $query->where(function($q) use ($date) {
                    $q->where('type', 'specific_date')
                      ->where('specific_date', $date->format('Y-m-d'));
                })->orWhere(function($q) use ($date) {
                    $q->where('type', 'date_range')
                      ->where('start_date', '<=', $date)
                      ->where('end_date', '>=', $date);
                });
            })
            ->delete();

        // Audit log
        if ($deletedCount > 0) {
            Log::info('Date unblocked', [
                'doctor_id' => $doctor->id,
                'subdomain_id' => $subdomain->id,
                'date' => $date->format('Y-m-d'),
                'slots_unblocked' => $deletedCount,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        }

        return $deletedCount;
    }
}

