<?php

namespace App\Http\Controllers;

use App\Models\DoctorAvailability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function index()
    {
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor()) {
            abort(403, 'Only doctors can manage availability');
        }

        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
            ->orderBy('type')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('availability.index', compact('availabilities', 'doctor'));
    }

    public function create()
    {
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor()) {
            abort(403, 'Only doctors can manage availability');
        }

        return view('availability.create');
    }

    public function store(Request $request)
    {
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor()) {
            abort(403, 'Only doctors can manage availability');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:weekly,specific_date,date_range'],
            'day_of_week' => ['nullable', 'integer', 'min:0', 'max:6'],
            'specific_date' => ['nullable', 'date', 'required_if:type,specific_date'],
            'start_date' => ['nullable', 'date', 'required_if:type,date_range'],
            'end_date' => ['nullable', 'date', 'required_if:type,date_range', 'after_or_equal:start_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'in:15,30,45,60'],
            'is_available' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['doctor_id'] = $doctor->id;
        $validated['is_available'] = $validated['is_available'] ?? true;

        DoctorAvailability::create($validated);

        return redirect()->route('availability.index')
            ->with('success', 'Availability schedule created successfully.');
    }

    public function edit(DoctorAvailability $availability)
    {
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor() || $availability->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');
        }

        return view('availability.edit', compact('availability'));
    }

    public function update(Request $request, DoctorAvailability $availability)
    {
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor() || $availability->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:weekly,specific_date,date_range'],
            'day_of_week' => ['nullable', 'integer', 'min:0', 'max:6'],
            'specific_date' => ['nullable', 'date', 'required_if:type,specific_date'],
            'start_date' => ['nullable', 'date', 'required_if:type,date_range'],
            'end_date' => ['nullable', 'date', 'required_if:type,date_range', 'after_or_equal:start_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'in:15,30,45,60'],
            'is_available' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_available'] = $validated['is_available'] ?? true;

        $availability->update($validated);

        return redirect()->route('availability.index')
            ->with('success', 'Availability schedule updated successfully.');
    }

    public function destroy(DoctorAvailability $availability)
    {
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor() || $availability->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized');
        }

        $availability->delete();

        return redirect()->route('availability.index')
            ->with('success', 'Availability schedule deleted successfully.');
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => ['required', 'integer', 'exists:users,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $doctor = User::findOrFail($request->doctor_id);
        
        // Verify doctor is actually a doctor (not admin)
        if ($doctor->role !== 'doctor') {
            return response()->json(['slots' => []], 200);
        }
        
        // Security: Verify doctor is active
        if (!$doctor->is_active) {
            return response()->json(['slots' => []], 200);
        }
        
        // Security: Limit date range to prevent abuse (max 1 year in advance)
        $maxDate = now()->addYear();
        $requestedDate = Carbon::parse($request->date);
        if ($requestedDate->gt($maxDate)) {
            return response()->json([
                'error' => 'Date too far in advance',
                'message' => 'Appointments can only be booked up to 1 year in advance'
            ], 422);
        }
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        // Get weekly availability for this day
        $weeklyAvailability = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('type', 'weekly')
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        // Get specific date overrides (only available ones, not blocked)
        $specificAvailability = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('type', 'specific_date')
            ->where('specific_date', $date->format('Y-m-d'))
            ->where('is_available', true)
            ->first();

        // Get date range availability
        $rangeAvailability = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('type', 'date_range')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('is_available', true)
            ->first();

        // Check for full-day blocks (00:00 to 23:59) - only these make entire day unavailable
        // Must check that start_time is exactly 00:00 and end_time is exactly 23:59
        $fullDayBlocked = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('is_available', false)
            ->where(function($query) use ($date) {
                $query->where(function($q) use ($date) {
                    $q->where('type', 'specific_date')
                      ->where('specific_date', $date->format('Y-m-d'))
                      ->where(function($timeQ) {
                          // Check for full day block: 00:00 to 23:59
                          $timeQ->where(function($t) {
                              $t->where('start_time', '00:00:00')
                                ->where('end_time', '23:59:59');
                          })->orWhere(function($t) {
                              $t->where('start_time', '00:00')
                                ->where('end_time', '23:59');
                          })->orWhere(function($t) {
                              // Also check for 00:00:00 to 23:59:00
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
            return response()->json(['slots' => []]);
        }

        // Get partial hour blocks (to exclude from available slots)
        // Exclude full-day blocks (00:00 to 23:59) from hour blocks
        $allBlocks = DoctorAvailability::where('doctor_id', $doctor->id)
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
        
        // Filter out full-day blocks in PHP (simpler and more reliable)
        $hourBlocks = $allBlocks
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
                // Filter out full-day blocks (00:00 to 23:59)
                return !($block['start'] === '00:00' && $block['end'] === '23:59');
            })
            ->values()
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
        }

        // Get existing appointments for this date
        $existingAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->get()
            ->map(function($apt) {
                return [
                    'start' => Carbon::parse($apt->appointment_date)->format('H:i'),
                    'end' => Carbon::parse($apt->appointment_date)->addMinutes($apt->duration)->format('H:i'),
                ];
            })
            ->toArray();

        // Generate all possible slots
        if (is_object($availability) && method_exists($availability, 'getTimeSlots')) {
            $allSlots = $availability->getTimeSlots();
        } else {
            // Default availability - generate slots manually
            $allSlots = [];
            $start = Carbon::createFromFormat('H:i', $availability->start_time);
            $end = Carbon::createFromFormat('H:i', $availability->end_time);
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

        // Get blocked slot keys for quick lookup
        $blockedSlotKeys = [];
        foreach ($hourBlocks as $block) {
            $blockedSlotKeys[] = $block['start'] . '-' . $block['end'];
        }
        
        // Process all slots and mark availability status (show all slots, mark blocked/booked ones)
        $processedSlots = [];
        foreach ($allSlots as $slot) {
            $slotKey = $slot['start'] . '-' . $slot['end'];
            $isBlocked = in_array($slotKey, $blockedSlotKeys);
            $isBooked = false;
            
            // Check against existing appointments
            foreach ($existingAppointments as $booked) {
                $bookedStartMinutes = $this->timeToMinutes($booked['start']);
                $bookedEndMinutes = $this->timeToMinutes($booked['end']);
                $slotStartMinutes = $this->timeToMinutes($slot['start']);
                $slotEndMinutes = $this->timeToMinutes($slot['end']);
                
                if ($slotStartMinutes < $bookedEndMinutes && $slotEndMinutes > $bookedStartMinutes) {
                    $isBooked = true;
                    break;
                }
            }
            
            // Add slot with availability status
            $slot['is_available'] = !$isBlocked && !$isBooked;
            $slot['is_blocked'] = $isBlocked;
            $slot['is_booked'] = $isBooked;
            $processedSlots[] = $slot;
        }

        return response()->json([
            'slots' => array_values($processedSlots), // All slots with is_available flag
            'slot_duration' => is_object($availability) && isset($availability->slot_duration) ? $availability->slot_duration : 30,
        ]);
    }

    public function quickSetAvailability(Request $request)
    {
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'action' => ['required', 'in:block_day,block_hours,unblock'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_if:action,block_hours'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_if:action,block_hours', 'after:start_time'],
        ]);

        $date = Carbon::parse($validated['date']);
        
        if ($validated['action'] === 'unblock') {
            // Security: Only allow unblocking future dates or today
            if ($date->lt(now()->startOfDay())) {
                return response()->json([
                    'error' => 'Invalid date',
                    'message' => 'Cannot unblock time slots in the past'
                ], 422);
            }
            
            // Delete all blocked slots for this specific date (only for this doctor)
            $deletedCount = DB::table('blocked_slots')
                ->where('doctor_id', $doctor->id) // Security: Ensure doctor can only unblock their own slots
                ->where('slot_date', $date->format('Y-m-d'))
                ->delete();
            
            // Also delete old availability entries for backward compatibility
            DoctorAvailability::where('doctor_id', $doctor->id)
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
            
            return response()->json([
                'success' => true, 
                'message' => "Successfully unblocked {$deletedCount} time slot(s)"
            ]);
        }
        
        if ($validated['action'] === 'block_day') {
            // Check if already exists
            $existing = DoctorAvailability::where('doctor_id', $doctor->id)
                ->where('type', 'specific_date')
                ->where('specific_date', $date->format('Y-m-d'))
                ->where('is_available', false)
                ->first();
            
            if ($existing) {
                return response()->json(['success' => true, 'message' => 'Day already blocked']);
            }
            
            // Create block for entire day (9 AM to 5 PM as default, but marked as unavailable)
            DoctorAvailability::create([
                'doctor_id' => $doctor->id,
                'type' => 'specific_date',
                'specific_date' => $date->format('Y-m-d'),
                'start_time' => '00:00',
                'end_time' => '23:59',
                'slot_duration' => 30,
                'is_available' => false,
                'notes' => 'Blocked day',
            ]);
            
            return response()->json(['success' => true, 'message' => 'Day blocked successfully']);
        }
        
        if ($validated['action'] === 'block_hours') {
            // Generate all 30-minute slots within the blocked time range
            $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
            $endTime = Carbon::createFromFormat('H:i', $validated['end_time']);
            $slotDate = $date->format('Y-m-d');
            
            $blockedCount = 0;
            $current = $startTime->copy();
            
            // Generate slots in 30-minute intervals
            while ($current->copy()->addMinutes(30)->lte($endTime)) {
                $slotStart = $current->format('H:i');
                $slotEnd = $current->copy()->addMinutes(30)->format('H:i');
                
                // Check if slot already exists
                $exists = \DB::table('blocked_slots')
                    ->where('doctor_id', $doctor->id)
                    ->where('slot_date', $slotDate)
                    ->where('slot_start_time', $slotStart)
                    ->where('slot_end_time', $slotEnd)
                    ->exists();
                
                if (!$exists) {
                    \DB::table('blocked_slots')->insert([
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
            
            return response()->json([
                'success' => true, 
                'message' => "Successfully blocked {$blockedCount} time slot(s)"
            ]);
        }
        
        return response()->json(['error' => 'Invalid action'], 400);
    }

    public function getDateAvailability(Request $request)
    {
        try {
            $doctor = auth()->user();
            
            if (!$doctor) {
                \Log::error('getDateAvailability: No authenticated user');
                return response()->json(['error' => 'Unauthorized', 'message' => 'Please log in'], 401);
            }
            
            // Check if user is a doctor
            if (!method_exists($doctor, 'isDoctor')) {
                \Log::error('getDateAvailability: isDoctor method does not exist on User model');
                // Fallback: check role directly
                if ($doctor->role !== 'doctor') {
                    return response()->json(['error' => 'Unauthorized', 'message' => 'Only doctors can access this'], 403);
                }
            } elseif (!$doctor->isDoctor()) {
                \Log::error('getDateAvailability: User is not a doctor. Role: ' . ($doctor->role ?? 'null'));
                return response()->json(['error' => 'Unauthorized', 'message' => 'Only doctors can access this'], 403);
            }

            $request->validate([
                'date' => ['required', 'date'],
            ]);

            // Parse date safely
            try {
                $date = Carbon::parse($request->date);
            } catch (\Exception $e) {
                \Log::error('Invalid date format: ' . $request->date);
                return response()->json([
                    'error' => 'Invalid date format',
                    'message' => 'Please provide a valid date'
                ], 422);
            }
            $dayOfWeek = $date->dayOfWeek;
            $dateString = $date->format('Y-m-d');

            // Check if table exists (for deployment safety)
            if (!Schema::hasTable('doctor_availabilities')) {
                \Log::error('doctor_availabilities table does not exist');
                return response()->json([
                    'error' => 'Database table not found',
                    'message' => 'Please run migrations: php artisan migrate'
                ], 500);
            }

            // Get blocked slots from the new blocked_slots table
            $blockedSlots = DB::table('blocked_slots')
                ->where('doctor_id', $doctor->id)
                ->where('slot_date', $dateString)
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
                        'id' => $block->id,
                        'type' => 'blocked_slot',
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_available' => false,
                        'notes' => $block->notes ?? 'Blocked',
                    ];
                })
                ->values();
            
            // Also get old availability entries for backward compatibility
            try {
                $specificDateEntries = DoctorAvailability::where('doctor_id', $doctor->id)
                    ->where('type', 'specific_date')
                    ->where('specific_date', $dateString)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching specific_date entries: ' . $e->getMessage());
                $specificDateEntries = collect([]);
            }
            
            try {
                $dateRangeEntries = DoctorAvailability::where('doctor_id', $doctor->id)
                    ->where('type', 'date_range')
                    ->where('start_date', '<=', $dateString)
                    ->where('end_date', '>=', $dateString)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching date_range entries: ' . $e->getMessage());
                $dateRangeEntries = collect([]);
            }
            
            try {
                $weeklyEntries = DoctorAvailability::where('doctor_id', $doctor->id)
                    ->where('type', 'weekly')
                    ->where('day_of_week', $dayOfWeek)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching weekly entries: ' . $e->getMessage());
                $weeklyEntries = collect([]);
            }
            
            // Combine all entries (blocked slots first, then availability entries)
            $availabilities = $blockedSlots
                ->merge($specificDateEntries)
                ->merge($dateRangeEntries)
                ->merge($weeklyEntries)
                ->map(function($avail) {
                    try {
                        // Handle both arrays (from blocked_slots) and objects (from DoctorAvailability)
                        $isArray = is_array($avail);
                        
                        // Safely extract time, handling null or different formats
                        $startTime = null;
                        $endTime = null;
                        
                        $startTimeValue = $isArray ? ($avail['start_time'] ?? null) : ($avail->start_time ?? null);
                        $endTimeValue = $isArray ? ($avail['end_time'] ?? null) : ($avail->end_time ?? null);
                        
                        if ($startTimeValue) {
                            $timeStr = (string) $startTimeValue;
                            if (strlen($timeStr) >= 5) {
                                $startTime = substr($timeStr, 0, 5);
                            } else {
                                $startTime = $timeStr;
                            }
                        }
                        
                        if ($endTimeValue) {
                            $timeStr = (string) $endTimeValue;
                            if (strlen($timeStr) >= 5) {
                                $endTime = substr($timeStr, 0, 5);
                            } else {
                                $endTime = $timeStr;
                            }
                        }
                        
                        $result = [
                            'id' => $isArray ? ($avail['id'] ?? null) : ($avail->id ?? null),
                            'type' => $isArray ? ($avail['type'] ?? 'blocked_slot') : ($avail->type ?? 'unknown'),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'is_available' => (bool) ($isArray ? ($avail['is_available'] ?? false) : ($avail->is_available ?? true)),
                            'notes' => $isArray ? ($avail['notes'] ?? null) : ($avail->notes ?? null),
                        ];
                        
                        return $result;
                    } catch (\Exception $e) {
                        $availId = is_array($avail) ? ($avail['id'] ?? 'unknown') : ($avail->id ?? 'unknown');
                        \Log::error('Error mapping availability ID ' . $availId . ': ' . $e->getMessage());
                        return null;
                    }
                })
                ->filter(function($avail) {
                    // Only return entries with valid times
                    return $avail !== null && $avail['start_time'] !== null && $avail['end_time'] !== null;
                })
                ->values();

            // Convert to array to ensure proper JSON encoding
            $availabilitiesArray = $availabilities->toArray();
            
            // Debug logging (only in development)
            if (config('app.debug')) {
                \Log::info('getDateAvailability - Date: ' . $dateString . ', Doctor ID: ' . $doctor->id);
                \Log::info('getDateAvailability - Found ' . count($availabilitiesArray) . ' entries');
            }

            return response()->json([
                'availabilities' => $availabilitiesArray
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('getDateAvailability validation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Invalid request',
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('getDateAvailability error: ' . $e->getMessage());
            \Log::error('getDateAvailability file: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('getDateAvailability trace: ' . substr($e->getTraceAsString(), 0, 1000)); // Limit trace length
            
            $errorMessage = 'An error occurred while loading availability';
            if (config('app.debug')) {
                $errorMessage = $e->getMessage() . ' in ' . basename($e->getFile()) . ':' . $e->getLine();
            }
            
            return response()->json([
                'error' => 'Failed to load availability',
                'message' => $errorMessage
            ], 500);
        }
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
}
