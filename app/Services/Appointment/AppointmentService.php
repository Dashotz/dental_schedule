<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use App\Models\Subdomain;
use App\Services\Tenant\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * Create a new appointment
     */
    public function create(array $data, ?Subdomain $subdomain = null): Appointment
    {
        $subdomain = $subdomain ?? TenantContext::ensureSubdomain();

        // Combine date and time
        $appointmentDateTime = $data['appointment_date'] . ' ' . $data['appointment_time'];

        $appointment = Appointment::create([
            'subdomain_id' => $subdomain->id,
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'] ?? null,
            'appointment_date' => $appointmentDateTime,
            'duration' => $data['duration'] ?? 30,
            'type' => $data['type'],
            'status' => $data['status'] ?? 'scheduled',
            'reason' => $data['reason'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Clear tenant-specific caches
        $this->clearAppointmentCaches($subdomain->id);

        // Audit log
        Log::info('Appointment created', [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'subdomain_id' => $subdomain->id,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return $appointment;
    }

    /**
     * Update an existing appointment
     */
    public function update(Appointment $appointment, array $data): Appointment
    {
        TenantContext::verifyOwnership($appointment, 'Appointment');

        // Combine date and time if both provided
        if (isset($data['appointment_date']) && isset($data['appointment_time'])) {
            $appointmentDateTime = $data['appointment_date'] . ' ' . $data['appointment_time'];
        } else {
            $appointmentDateTime = $appointment->appointment_date;
        }

        $oldStatus = $appointment->status;
        $oldDate = $appointment->appointment_date;

        $appointment->update([
            'patient_id' => $data['patient_id'] ?? $appointment->patient_id,
            'doctor_id' => $data['doctor_id'] ?? $appointment->doctor_id,
            'appointment_date' => $appointmentDateTime,
            'duration' => $data['duration'] ?? $appointment->duration,
            'type' => $data['type'] ?? $appointment->type,
            'status' => $data['status'] ?? $appointment->status,
            'reason' => $data['reason'] ?? $appointment->reason,
            'notes' => $data['notes'] ?? $appointment->notes,
        ]);

        // Clear tenant-specific caches
        $this->clearAppointmentCaches($appointment->subdomain_id);

        // Audit log
        Log::info('Appointment updated', [
            'appointment_id' => $appointment->id,
            'subdomain_id' => $appointment->subdomain_id,
            'old_status' => $oldStatus,
            'new_status' => $appointment->status,
            'old_date' => $oldDate,
            'new_date' => $appointment->appointment_date,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return $appointment->fresh();
    }

    /**
     * Delete an appointment
     */
    public function delete(Appointment $appointment): bool
    {
        TenantContext::verifyOwnership($appointment, 'Appointment');

        $subdomainId = $appointment->subdomain_id;
        $appointmentId = $appointment->id;
        $patientId = $appointment->patient_id;

        $deleted = $appointment->delete();

        if ($deleted) {
            // Clear tenant-specific caches
            $this->clearAppointmentCaches($subdomainId);

            // Audit log
            Log::info('Appointment deleted', [
                'appointment_id' => $appointmentId,
                'patient_id' => $patientId,
                'subdomain_id' => $subdomainId,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        }

        return $deleted;
    }

    /**
     * Get appointments for a date range
     */
    public function getAppointmentsInRange(Carbon $startDate, Carbon $endDate, ?int $subdomainId = null): \Illuminate\Database\Eloquent\Collection
    {
        $subdomainId = $subdomainId ?? TenantContext::getSubdomainId();

        return Appointment::where('subdomain_id', $subdomainId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->with('patient')
            ->orderBy('appointment_date')
            ->get();
    }

    /**
     * Get appointments for today
     */
    public function getTodayAppointments(?int $subdomainId = null): \Illuminate\Database\Eloquent\Collection
    {
        $subdomainId = $subdomainId ?? TenantContext::getSubdomainId();
        $today = Carbon::today();

        return Appointment::where('subdomain_id', $subdomainId)
            ->whereDate('appointment_date', $today)
            ->with('patient', 'doctor')
            ->orderBy('appointment_date')
            ->get();
    }

    /**
     * Clear appointment-related caches
     */
    protected function clearAppointmentCaches(?int $subdomainId): void
    {
        if (!$subdomainId) {
            return;
        }

        Cache::forget(TenantContext::getCacheKey('dashboard_stats', $subdomainId));
    }
}

