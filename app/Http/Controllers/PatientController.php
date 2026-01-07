<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\RegistrationLink;
use App\Traits\SanitizesInput;
use App\Traits\UsesSubdomainViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    use SanitizesInput, UsesSubdomainViews;

    public function showRegistrationForm()
    {
        return view('subdomain-template.register');
    }

    public function store(Request $request, $token = null)
    {
        if (!$token) {
            return redirect()->route('home')
                ->with('error', 'Registration is only available through a valid registration link.');
        }

        // Security: Validate token format first
        if (!RegistrationLink::isValidTokenFormat($token)) {
            \Log::warning('Invalid registration token format in store attempt', [
                'token_length' => strlen($token),
                'ip' => $request->ip(),
            ]);
            return redirect()->route('home')
                ->with('error', 'Invalid or expired registration link.');
        }

        $registrationLink = RegistrationLink::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$registrationLink || !$registrationLink->isUsable()) {
            \Log::warning('Registration link validation failed in store', [
                'token_exists' => $registrationLink !== null,
                'is_usable' => $registrationLink ? $registrationLink->isUsable() : false,
                'ip' => $request->ip(),
            ]);
            return redirect()->route('home')
                ->with('error', 'Invalid or expired registration link.');
        }

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
        $doctors = \App\Models\User::where('is_active', true)
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
            
            // Check if the selected time falls within any available slot
            // The time should be >= slot start and < slot end (or within the slot duration)
            foreach ($slots as $slot) {
                $slotStart = $slot['start'];
                $slotEnd = $slot['end'];
                
                // Check if appointment time is within this slot
                // Appointment time should be >= slot start and < slot end
                if ($appointmentTime >= $slotStart && $appointmentTime < $slotEnd) {
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

            // Increment registration link usage and log
            if ($token && $registrationLink) {
                $registrationLink->increment('used_count');
                
                \Log::info('Registration link used successfully', [
                    'link_id' => $registrationLink->id,
                    'subdomain_id' => $registrationLink->subdomain_id,
                    'used_count' => $registrationLink->used_count,
                    'max_uses' => $registrationLink->max_uses,
                    'ip' => $request->ip(),
                ]);
            }

            DB::commit();

            // Clear caches
            cache()->forget('patients_list');
            cache()->forget('dashboard_stats');

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

        // Get specific date overrides (only available ones, not blocked)
        $specificAvailability = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
            ->where('type', 'specific_date')
            ->where('specific_date', $date->format('Y-m-d'))
            ->where('is_available', true)
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

        // Get blocked slots from the new blocked_slots table
        $blockedSlots = \DB::table('blocked_slots')
            ->where('doctor_id', $doctorId)
            ->where('slot_date', $date->format('Y-m-d'))
            ->get()
            ->map(function($block) {
                $startTime = $block->slot_start_time;
                $endTime = $block->slot_end_time;
                
                // Normalize time format
                if (is_string($startTime)) {
                    $startTime = strlen($startTime) > 5 ? substr($startTime, 0, 5) : $startTime;
                } else {
                    $startTime = (string) $startTime;
                    $startTime = strlen($startTime) > 5 ? substr($startTime, 0, 5) : $startTime;
                }
                
                if (is_string($endTime)) {
                    $endTime = strlen($endTime) > 5 ? substr($endTime, 0, 5) : $endTime;
                } else {
                    $endTime = (string) $endTime;
                    $endTime = strlen($endTime) > 5 ? substr($endTime, 0, 5) : $endTime;
                }
                
                return [
                    'start' => $startTime,
                    'end' => $endTime,
                ];
            })
            ->toArray();
        
        // Also check old availability entries for backward compatibility
        $oldBlocks = \App\Models\DoctorAvailability::where('doctor_id', $doctorId)
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
            ->get();
        
        // Convert old blocks to slot format (generate individual slots)
        foreach ($oldBlocks as $block) {
            $blockStart = \Carbon\Carbon::createFromFormat('H:i', substr($block->start_time, 0, 5));
            $blockEnd = \Carbon\Carbon::createFromFormat('H:i', substr($block->end_time, 0, 5));
            
            // Skip full-day blocks (00:00 to 23:59)
            if ($blockStart->format('H:i') === '00:00' && $blockEnd->format('H:i') === '23:59') {
                continue;
            }
            
            $current = $blockStart->copy();
            while ($current->copy()->addMinutes(30)->lte($blockEnd)) {
                $slotStart = $current->format('H:i');
                $slotEnd = $current->copy()->addMinutes(30)->format('H:i');
                
                $blockedSlots[] = [
                    'start' => $slotStart,
                    'end' => $slotEnd,
                ];
                
                $current->addMinutes(30);
            }
        }
        
        $hourBlocks = $blockedSlots;
        
        // Debug logging (only in development)
        if (config('app.debug')) {
            \Log::info('PatientController - Hour blocks for date ' . $date->format('Y-m-d') . ', doctor ' . $doctorId . ': ' . json_encode($hourBlocks));
        }

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
                $blockStart = $block['start'];
                $blockEnd = $block['end'];
                $slotStart = $slot['start'];
                $slotEnd = $slot['end'];
                
                // Convert times to minutes for reliable comparison
                $blockStartMinutes = $this->timeToMinutes($blockStart);
                $blockEndMinutes = $this->timeToMinutes($blockEnd);
                $slotStartMinutes = $this->timeToMinutes($slotStart);
                $slotEndMinutes = $this->timeToMinutes($slotEnd);
                
                // Check if slot overlaps with blocked time
                // Slot is blocked if it starts before block ends AND ends after block starts
                if ($slotStartMinutes < $blockEndMinutes && $slotEndMinutes > $blockStartMinutes) {
                    if (config('app.debug')) {
                        \Log::info("PatientController - Slot {$slotStart}-{$slotEnd} (minutes: {$slotStartMinutes}-{$slotEndMinutes}) overlaps with block {$blockStart}-{$blockEnd} (minutes: {$blockStartMinutes}-{$blockEndMinutes})");
                    }
                    return false;
                }
            }
            
            return true;
        });

        return array_values($availableSlots);
    }
    
    /**
     * Convert time string (HH:MM) to minutes for comparison
     */
    private function timeToMinutes($timeString)
    {
        $parts = explode(':', $timeString);
        $hours = (int) $parts[0];
        $minutes = (int) ($parts[1] ?? 0);
        return $hours * 60 + $minutes;
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

        // Eager load relationships to prevent N+1 queries
        $patients = $query->with(['appointments' => function($q) {
                $q->latest()->limit(3);
            }])
            ->latest()
            ->paginate(15);
        
        return $this->subdomainView('patient.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        // Authorization: Ensure user is authenticated (doctor)
        // Note: Route already has auth:web middleware, but adding explicit check for security
        if (!auth()->check()) {
            \Log::warning('Unauthorized patient view attempt', [
                'patient_id' => $patient->id,
                'ip' => request()->ip(),
            ]);
            abort(403, 'Unauthorized access.');
        }
        
        $patient->load(['appointments', 'treatmentPlans', 'teethRecords', 'treatments']);
        
        // If AJAX request, return modal content
        if (request()->ajax()) {
            return response()->json([
                'html' => view($this->getSubdomainViewPath() . '.patient.partials.view-modal', compact('patient'))->render()
            ]);
        }
        
        // Redirect to index page since we're using modals now
        return redirect()->route('patients.index');
    }

    public function edit(Patient $patient)
    {
        // If AJAX request, return modal content
        if (request()->ajax()) {
            return response()->json([
                'html' => view($this->getSubdomainViewPath() . '.patient.partials.edit-modal', compact('patient'))->render()
            ]);
        }
        
        return $this->subdomainView('patient.edit', compact('patient'));
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

        // Clear caches
        cache()->forget('patients_list');
        cache()->forget('dashboard_stats');

        // If AJAX request, return JSON response
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient information updated successfully.'
            ]);
        }

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient information updated successfully.');
    }
}
