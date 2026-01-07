<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Traits\SanitizesInput;
use App\Traits\UsesSubdomainViews;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use SanitizesInput, UsesSubdomainViews;

    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->latest('appointment_date')
            ->paginate(20);
        
        return $this->subdomainView('appointment.index', compact('appointments'));
    }

    public function create()
    {
        // Cache patients and doctors lists since they're used in dropdowns
        $patients = cache()->remember('patients_list', 1800, function() {
            return Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        });
        
        $doctors = cache()->remember('doctors_list', 1800, function() {
            return User::where('is_active', true)->get(['id', 'name']);
        });
        
        $selectedPatientId = request('patient_id');
        
        return $this->subdomainView('appointment.create', compact('patients', 'doctors', 'selectedPatientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['nullable', 'exists:users,id'],
            'appointment_date' => ['required', 'date', 'after:now'],
            'appointment_time' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'duration' => ['nullable', 'integer', 'min:15'],
            'type' => ['required', 'in:consultation,cleaning,procedure,follow_up,emergency,other'],
            'status' => ['nullable', 'in:scheduled,confirmed,in_progress,completed,cancelled,no_show'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Sanitize inputs
        $sanitized = $this->sanitizeInput($validated);
        $appointmentDateTime = $sanitized['appointment_date'] . ' ' . $sanitized['appointment_time'];

        $appointment = Appointment::create([
            'patient_id' => $sanitized['patient_id'],
            'doctor_id' => $sanitized['doctor_id'] ?? null,
            'appointment_date' => $appointmentDateTime,
            'duration' => $sanitized['duration'] ?? 30,
            'type' => $sanitized['type'],
            'status' => $sanitized['status'] ?? 'scheduled',
            'reason' => $sanitized['reason'] ?? null,
            'notes' => $sanitized['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Clear dashboard cache if exists
        cache()->forget('dashboard_stats');

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        // Authorization check: Only allow access if user is the doctor or created the appointment
        $user = auth()->user();
        if ($appointment->doctor_id && $appointment->doctor_id !== $user->id && 
            $appointment->created_by !== $user->id) {
            abort(403, 'Unauthorized access to this appointment.');
        }
        
        $appointment->load(['patient', 'doctor', 'createdBy', 'treatments']);
        return $this->subdomainView('appointment.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        // Authorization check: Only allow access if user is the doctor or created the appointment
        $user = auth()->user();
        if ($appointment->doctor_id && $appointment->doctor_id !== $user->id && 
            $appointment->created_by !== $user->id) {
            abort(403, 'Unauthorized access to this appointment.');
        }
        
        // Use cached lists
        $patients = cache()->remember('patients_list', 1800, function() {
            return Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        });
        
        $doctors = cache()->remember('doctors_list', 1800, function() {
            return User::where('is_active', true)->get(['id', 'name']);
        });
        
        return $this->subdomainView('appointment.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        // Authorization check: Only allow access if user is the doctor or created the appointment
        $user = auth()->user();
        if ($appointment->doctor_id && $appointment->doctor_id !== $user->id && 
            $appointment->created_by !== $user->id) {
            abort(403, 'Unauthorized access to this appointment.');
        }
        
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['nullable', 'exists:users,id'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'duration' => ['nullable', 'integer', 'min:15'],
            'type' => ['required', 'in:consultation,cleaning,procedure,follow_up,emergency,other'],
            'status' => ['required', 'in:scheduled,confirmed,in_progress,completed,cancelled,no_show'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Sanitize inputs
        $sanitized = $this->sanitizeInput($validated);
        $appointmentDateTime = $sanitized['appointment_date'] . ' ' . $sanitized['appointment_time'];

        $appointment->update([
            'patient_id' => $sanitized['patient_id'],
            'doctor_id' => $sanitized['doctor_id'] ?? null,
            'appointment_date' => $appointmentDateTime,
            'duration' => $sanitized['duration'] ?? 30,
            'type' => $sanitized['type'],
            'status' => $sanitized['status'],
            'reason' => $sanitized['reason'] ?? null,
            'notes' => $sanitized['notes'] ?? null,
        ]);

        // Clear dashboard cache if exists
        cache()->forget('dashboard_stats');

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        // Authorization check: Only allow access if user is the doctor or created the appointment
        $user = auth()->user();
        if ($appointment->doctor_id && $appointment->doctor_id !== $user->id && 
            $appointment->created_by !== $user->id) {
            abort(403, 'Unauthorized access to this appointment.');
        }
        
        $appointment->delete();

        // Clear dashboard cache if exists
        cache()->forget('dashboard_stats');

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}
