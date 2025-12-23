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

        // Check doctor availability before creating appointment
        $appointmentDate = \Carbon\Carbon::parse($sanitized['appointment_date']);
        $appointmentTime = $sanitized['appointment_time'];
        $appointmentDateTime = $appointmentDate->format('Y-m-d') . ' ' . $appointmentTime;

        // Get all active doctors
        $doctors = \App\Models\User::where('role', 'doctor')
            ->where('is_active', true)
            ->get();

        if ($doctors->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No doctors are currently available. Please contact the clinic.');
        }

        // Check if any doctor is available for this date/time
        $availableDoctor = null;
        foreach ($doctors as $doctor) {
            $slots = $this->getAvailableSlotsForDoctor($doctor->id, $appointmentDate);
            
            // Check if the selected time is in available slots
            foreach ($slots as $slot) {
                if ($appointmentTime >= $slot['start'] && $appointmentTime < $slot['end']) {
                    $availableDoctor = $doctor;
                    break 2; // Break both loops
                }
            }
        }

        if (!$availableDoctor) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The selected date and time are not available. Please choose another time slot.');
        }

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

            // Create appointment with assigned doctor
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $availableDoctor->id,
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

    /**
     * Get available time slots for a doctor on a specific date
     */
    private function getAvailableSlotsForDoctor($doctorId, $date)
    {
        $dayOfWeek = $date->dayOfWeek;

        // Get weekly availability for this day
        $weeklyAvailability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->where('type', 'weekly')
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        // Get specific date overrides
        $specificAvailability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->where('type', 'specific_date')
            ->where('specific_date', $date->format('Y-m-d'))
            ->first();

        // Get date range availability
        $rangeAvailability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->where('type', 'date_range')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('is_available', true)
            ->first();

        // Check for full-day blocks (00:00 to 23:59) - only these make entire day unavailable
        $fullDayBlocked = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->where('is_available', false)
            ->where(function($query) use ($date) {
                $query->where(function($q) use ($date) {
                    $q->where('type', 'specific_date')
                      ->where('specific_date', $date->format('Y-m-d'))
                      ->where(function($timeQ) {
                          $timeQ->where(function($t) {
                              $t->where('start_time', '00:00:00')
                                ->where('end_time', '23:59:59');
                          })->orWhere(function($t) {
                              $t->where('start_time', '00:00')
                                ->where('end_time', '23:59');
                          })->orWhere(function($t) {
                              $t->where('start_time', '<=', '00:00')
                                ->where('end_time', '>=', '23:59');
                          });
                      });
                })->orWhere(function($q) use ($date) {
                    $q->where('type', 'date_range')
                      ->where('start_date', '<=', $date)
                      ->where('end_date', '>=', $date)
                      ->where(function($timeQ) {
                          $timeQ->where(function($t) {
                              $t->where('start_time', '00:00:00')
                                ->where('end_time', '23:59:59');
                          })->orWhere(function($t) {
                              $t->where('start_time', '00:00')
                                ->where('end_time', '23:59');
                          })->orWhere(function($t) {
                              $t->where('start_time', '<=', '00:00')
                                ->where('end_time', '>=', '23:59');
                          });
                      });
                });
            })
            ->exists();

        if ($fullDayBlocked) {
            return [];
        }

        // Get partial hour blocks (to exclude from available slots)
        // Exclude full-day blocks (00:00 to 23:59) from hour blocks
        $hourBlocks = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
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
            ->where(function($timeQ) {
                // Exclude full-day blocks - only get partial hour blocks
                $timeQ->where(function($t) {
                    $t->where(function($notFull) {
                        $notFull->where('start_time', '!=', '00:00')
                                ->orWhere('end_time', '!=', '23:59')
                                ->orWhere('start_time', '!=', '00:00:00')
                                ->orWhere('end_time', '!=', '23:59:59');
                    });
                });
            })
            ->get()
            ->map(function($block) {
                $startTime = $block->start_time;
                $endTime = $block->end_time;
                
                // Normalize time format
                if (strlen($startTime) > 5) {
                    $startTime = substr($startTime, 0, 5);
                }
                if (strlen($endTime) > 5) {
                    $endTime = substr($endTime, 0, 5);
                }
                
                return [
                    'start' => $startTime,
                    'end' => $endTime,
                ];
            })
            ->filter(function($block) {
                // Filter out any full-day blocks that might have slipped through
                return !($block['start'] === '00:00' && $block['end'] === '23:59');
            })
            ->toArray();

        // Use specific date override if exists, otherwise use weekly or range
        $availability = $specificAvailability ?? $rangeAvailability ?? $weeklyAvailability;

        // If no availability schedule is set, use default working hours (9 AM - 5 PM, 30 min slots)
        if (!$availability) {
            $availability = (object)[
                'start_time' => '09:00',
                'end_time' => '17:00',
                'slot_duration' => 30,
                'is_available' => true,
            ];
        } elseif (!$availability->is_available) {
            return [];
        }

        // Get existing appointments for this date
        $existingAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->get();

        // Generate all possible slots
        if (is_object($availability) && method_exists($availability, 'getTimeSlots')) {
            $allSlots = $availability->getTimeSlots();
        } else {
            // Default availability - generate slots manually
            $allSlots = [];
            $start = \Carbon\Carbon::createFromFormat('H:i', $availability->start_time);
            $end = \Carbon\Carbon::createFromFormat('H:i', $availability->end_time);
            $duration = $availability->slot_duration ?? 30;
            
            $current = $start->copy();
            while ($current->copy()->addMinutes($duration)->lte($end)) {
                $allSlots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $current->copy()->addMinutes($duration)->format('H:i'),
                ];
                $current->addMinutes($duration);
            }
        }

        // Filter out booked slots and blocked hours
        $availableSlots = array_filter($allSlots, function($slot) use ($existingAppointments, $hourBlocks) {
            // Check against existing appointments
            foreach ($existingAppointments as $apt) {
                $aptStart = \Carbon\Carbon::parse($apt->appointment_date)->format('H:i');
                $aptEnd = \Carbon\Carbon::parse($apt->appointment_date)->addMinutes($apt->duration)->format('H:i');
                
                if (($slot['start'] >= $aptStart && $slot['start'] < $aptEnd) ||
                    ($slot['end'] > $aptStart && $slot['end'] <= $aptEnd) ||
                    ($slot['start'] <= $aptStart && $slot['end'] >= $aptEnd)) {
                    return false;
                }
            }
            
            // Check against blocked hours
            foreach ($hourBlocks as $block) {
                if (($slot['start'] >= $block['start'] && $slot['start'] < $block['end']) ||
                    ($slot['end'] > $block['start'] && $slot['end'] <= $block['end']) ||
                    ($slot['start'] <= $block['start'] && $slot['end'] >= $block['end'])) {
                    return false;
                }
            }
            
            return true;
        });

        return array_values($availableSlots);
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
