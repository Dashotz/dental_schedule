<?php

namespace App\Services\Patient;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Subdomain;
use App\Services\Tenant\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PatientService
{
    /**
     * Create a new patient
     */
    public function create(array $data, ?Subdomain $subdomain = null): Patient
    {
        $subdomain = $subdomain ?? TenantContext::ensureSubdomain();
        
        $patient = Patient::create([
            'subdomain_id' => $subdomain->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'phone_alt' => $data['phone_alt'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'zip_code' => $data['zip_code'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'medical_history' => $data['medical_history'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'medications' => $data['medications'] ?? null,
            'insurance_provider' => $data['insurance_provider'] ?? null,
            'insurance_policy_number' => $data['insurance_policy_number'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        // Clear tenant-specific caches
        $this->clearPatientCaches($subdomain->id);

        // Audit log
        Log::info('Patient created', [
            'patient_id' => $patient->id,
            'subdomain_id' => $subdomain->id,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return $patient;
    }

    /**
     * Update an existing patient
     */
    public function update(Patient $patient, array $data): Patient
    {
        TenantContext::verifyOwnership($patient, 'Patient');

        $patient->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'phone_alt' => $data['phone_alt'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'zip_code' => $data['zip_code'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'medical_history' => $data['medical_history'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'medications' => $data['medications'] ?? null,
            'insurance_provider' => $data['insurance_provider'] ?? null,
            'insurance_policy_number' => $data['insurance_policy_number'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        // Clear tenant-specific caches
        $this->clearPatientCaches($patient->subdomain_id);

        // Audit log
        Log::info('Patient updated', [
            'patient_id' => $patient->id,
            'subdomain_id' => $patient->subdomain_id,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return $patient->fresh();
    }

    /**
     * Delete a patient
     */
    public function delete(Patient $patient): bool
    {
        TenantContext::verifyOwnership($patient, 'Patient');

        $subdomainId = $patient->subdomain_id;
        $patientId = $patient->id;

        $deleted = $patient->delete();

        if ($deleted) {
            // Clear tenant-specific caches
            $this->clearPatientCaches($subdomainId);

            // Audit log
            Log::info('Patient deleted', [
                'patient_id' => $patientId,
                'subdomain_id' => $subdomainId,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        }

        return $deleted;
    }

    /**
     * Get patients list with caching
     */
    public function getPatientsList(?int $subdomainId = null): \Illuminate\Database\Eloquent\Collection
    {
        $subdomainId = $subdomainId ?? TenantContext::getSubdomainId();
        $cacheKey = TenantContext::getCacheKey('patients_list', $subdomainId);

        return Cache::remember($cacheKey, 1800, function() {
            return Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        });
    }

    /**
     * Create patient with appointment (for registration)
     */
    public function createWithAppointment(array $patientData, array $appointmentData, ?Subdomain $subdomain = null): array
    {
        $subdomain = $subdomain ?? TenantContext::ensureSubdomain();

        return DB::transaction(function() use ($patientData, $appointmentData, $subdomain) {
            // Create patient
            $patient = $this->create($patientData, $subdomain);

            // Create appointment
            $appointmentService = app(\App\Services\Appointment\AppointmentService::class);
            $appointment = $appointmentService->create(array_merge($appointmentData, [
                'patient_id' => $patient->id,
            ]), $subdomain);

            return [
                'patient' => $patient,
                'appointment' => $appointment,
            ];
        });
    }

    /**
     * Clear patient-related caches
     */
    protected function clearPatientCaches(?int $subdomainId): void
    {
        if (!$subdomainId) {
            return;
        }

        Cache::forget(TenantContext::getCacheKey('patients_list', $subdomainId));
        Cache::forget(TenantContext::getCacheKey('dashboard_stats', $subdomainId));
    }
}

