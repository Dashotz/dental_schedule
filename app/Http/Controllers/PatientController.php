<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\RegistrationLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function showRegistrationForm()
    {
        return view('patient.register');
    }

    /**
     * Sanitize input data
     */
    private function sanitizeInput($data)
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remove HTML tags and trim whitespace
                $sanitized[$key] = strip_tags(trim($value));
                // Remove null bytes
                $sanitized[$key] = str_replace("\0", '', $sanitized[$key]);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }

    public function store(Request $request, $token = null)
    {
        // Guard: Validate registration link if token provided
        if ($token) {
            $registrationLink = RegistrationLink::where('token', $token)
                ->where('is_active', true)
                ->first();

            if (!$registrationLink || !$registrationLink->isUsable()) {
                return redirect()->route('home')
                    ->with('error', 'Invalid or expired registration link.');
            }

            // Check subdomain status
            if ($registrationLink->subdomain) {
                if (!$registrationLink->subdomain->is_active) {
                    return redirect()->route('home')
                        ->with('error', 'This dental clinic website is currently disabled.');
                }

                $hasActiveSubscription = $registrationLink->subdomain->subscriptions()
                    ->where('status', 'active')
                    ->where('end_date', '>=', now())
                    ->exists();
                
                if (!$hasActiveSubscription) {
                    return redirect()->route('home')
                        ->with('error', 'This dental clinic subscription has expired.');
                }
            }
        } else {
            // If no token, registration is not allowed
            return redirect()->route('home')
                ->with('error', 'Registration is only available through a valid registration link.');
        }

        // Validate CAPTCHA first
        try {
            $request->validate([
                'captcha' => 'required|captcha',
            ], [
                'captcha.required' => 'Please complete the CAPTCHA verification.',
                'captcha.captcha' => 'Invalid CAPTCHA. Please try again.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        }

        $validated = $request->validate([
            // Patient information
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'phone_alt' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'state' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'zip_code' => ['nullable', 'string', 'max:20', 'regex:/^[\d\w\s\-]+$/'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'medical_history' => ['nullable', 'string', 'max:2000'],
            'allergies' => ['nullable', 'string', 'max:1000'],
            'medications' => ['nullable', 'string', 'max:1000'],
            'insurance_provider' => ['nullable', 'string', 'max:255'],
            'insurance_policy_number' => ['nullable', 'string', 'max:255'],
            
            // Appointment information
            'appointment_date' => ['required', 'date', 'after:today'],
            'appointment_time' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'appointment_type' => ['required', Rule::in(['consultation', 'cleaning', 'procedure', 'follow_up', 'emergency', 'other'])],
            'reason' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, and apostrophes.',
            'phone.regex' => 'Phone number format is invalid.',
            'phone_alt.regex' => 'Alternate phone number format is invalid.',
            'city.regex' => 'City can only contain letters, spaces, hyphens, and apostrophes.',
            'state.regex' => 'State can only contain letters, spaces, hyphens, and apostrophes.',
            'appointment_time.regex' => 'Invalid time format. Please use HH:MM format.',
        ]);

        // Sanitize all inputs
        $sanitized = $this->sanitizeInput($validated);

        try {
            DB::beginTransaction();

            // Create patient with sanitized data
            $patient = Patient::create([
                'first_name' => $sanitized['first_name'],
                'last_name' => $sanitized['last_name'],
                'date_of_birth' => $sanitized['date_of_birth'] ?? null,
                'gender' => $sanitized['gender'] ?? null,
                'email' => $sanitized['email'] ?? null,
                'phone' => $sanitized['phone'],
                'phone_alt' => $sanitized['phone_alt'] ?? null,
                'address' => $sanitized['address'] ?? null,
                'city' => $sanitized['city'] ?? null,
                'state' => $sanitized['state'] ?? null,
                'zip_code' => $sanitized['zip_code'] ?? null,
                'emergency_contact_name' => $sanitized['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $sanitized['emergency_contact_phone'] ?? null,
                'medical_history' => $sanitized['medical_history'] ?? null,
                'allergies' => $sanitized['allergies'] ?? null,
                'medications' => $sanitized['medications'] ?? null,
                'insurance_provider' => $sanitized['insurance_provider'] ?? null,
                'insurance_policy_number' => $sanitized['insurance_policy_number'] ?? null,
            ]);

            // Create appointment
            $appointmentDateTime = $sanitized['appointment_date'] . ' ' . $sanitized['appointment_time'];
            
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'appointment_date' => $appointmentDateTime,
                'type' => $sanitized['appointment_type'],
                'status' => 'scheduled',
                'reason' => $sanitized['reason'] ?? null,
                'notes' => $sanitized['notes'] ?? null,
                'duration' => 30, // Default 30 minutes
            ]);

            // Increment registration link usage
            if ($token && $registrationLink) {
                $registrationLink->increment('used_count');
            }

            DB::commit();

            return redirect()->route('home')
                ->with('success', 'Registration and appointment booking successful! Your appointment is scheduled for ' . $appointment->appointment_date->format('F d, Y \a\t g:i A'));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Patient registration error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    public function index()
    {
        $query = Patient::query();

        // Search functionality
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(15);
        return view('patient.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments', 'treatmentPlans', 'teethRecords', 'treatments']);
        return view('patient.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patient.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'phone_alt' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'state' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'zip_code' => ['nullable', 'string', 'max:20', 'regex:/^[\d\w\s\-]+$/'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\']+$/'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'medical_history' => ['nullable', 'string', 'max:2000'],
            'allergies' => ['nullable', 'string', 'max:1000'],
            'medications' => ['nullable', 'string', 'max:1000'],
            'insurance_provider' => ['nullable', 'string', 'max:255'],
            'insurance_policy_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Sanitize inputs
        $sanitized = $this->sanitizeInput($validated);
        $patient->update($sanitized);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient information updated successfully.');
    }
}
