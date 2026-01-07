<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use App\Traits\SanitizesInput;
use App\Traits\UsesSubdomainViews;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use SanitizesInput, UsesSubdomainViews;

    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->latest('appointment_date')
            ->paginate(20);
        
        return $this->subdomainView('appointment.index', compact('appointments'));
    }

    public function create()
    {
        // Cache patients and doctors lists with tenant-specific keys
        $subdomainId = TenantContext::getSubdomainId();
        $patients = cache()->remember(
            TenantContext::getCacheKey('patients_list', $subdomainId),
            1800,
            function() {
                return Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
            }
        );
        
        $doctors = cache()->remember(
            TenantContext::getCacheKey('doctors_list', $subdomainId),
            1800,
            function() {
                return User::where('is_active', true)->get(['id', 'name']);
            }
        );
        
        $selectedPatientId = request('patient_id');
        
        return $this->subdomainView('appointment.create', compact('patients', 'doctors', 'selectedPatientId'));
    }

    public function store(StoreRequest $request)
    {
        // Authorization via policy
        $this->authorize('create', Appointment::class);

        // Sanitize inputs
        $sanitized = $this->sanitizeInput($request->validated());
        
        // Use service to create appointment
        $appointment = $this->appointmentService->create($sanitized);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        // Authorization via policy
        $this->authorize('view', $appointment);
        
        $appointment->load(['patient', 'doctor', 'createdBy', 'treatments']);
        return $this->subdomainView('appointment.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        // Authorization via policy
        $this->authorize('update', $appointment);
        
        // Use cached lists with tenant-specific keys
        $subdomainId = TenantContext::getSubdomainId();
        $patients = cache()->remember(
            TenantContext::getCacheKey('patients_list', $subdomainId),
            1800,
            function() {
                return Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
            }
        );
        
        $doctors = cache()->remember(
            TenantContext::getCacheKey('doctors_list', $subdomainId),
            1800,
            function() {
                return User::where('is_active', true)->get(['id', 'name']);
            }
        );
        
        return $this->subdomainView('appointment.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(UpdateRequest $request, Appointment $appointment)
    {
        // Authorization via policy
        $this->authorize('update', $appointment);

        // Sanitize inputs
        $sanitized = $this->sanitizeInput($request->validated());
        
        // Use service to update appointment
        $this->appointmentService->update($appointment, $sanitized);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        // Authorization via policy
        $this->authorize('delete', $appointment);
        
        // Use service to delete appointment
        $this->appointmentService->delete($appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}
