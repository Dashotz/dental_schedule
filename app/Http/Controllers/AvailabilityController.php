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
        
        if ($doctor->role !== 'doctor' || !$doctor->is_active) {
            return response()->json(['slots' => []], 200);
        }
        
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

        $rangeAvailability = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('type', 'date_range')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('is_available', true)
            ->first();

        $fullDayBlocked = DoctorAvailability::where('doctor_id', $doctor->id)
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
            return response()->json(['slots' => []]);
        }

        $blockedSlots = DB::table('blocked_slots')
            ->where('doctor_id', $doctor->id)
            ->where('slot_date', $date->format('Y-m-d'))
            ->get()
            ->map(function($block) {
                $startTime = $this->normalizeTime($block->slot_start_time);
                $endTime = $this->normalizeTime($block->slot_end_time);
                return ['start' => $startTime, 'end' => $endTime];
            })
            ->toArray();
        
        $oldBlocks = DoctorAvailability::where('doctor_id', $doctor->id)
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
        
        foreach ($oldBlocks as $block) {
            $blockStart = Carbon::createFromFormat('H:i', substr($block->start_time, 0, 5));
            $blockEnd = Carbon::createFromFormat('H:i', substr($block->end_time, 0, 5));
            
            if ($blockStart->format('H:i') === '00:00' && $blockEnd->format('H:i') === '23:59') {
                continue;
            }
            
            $current = $blockStart->copy();
            while ($current->copy()->addMinutes(30)->lte($blockEnd)) {
                $blockedSlots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $current->copy()->addMinutes(30)->format('H:i'),
                ];
                $current->addMinutes(30);
            }
        }
        
        $hourBlocks = $blockedSlots;
        $availability = $specificAvailability ?? $rangeAvailability ?? $weeklyAvailability;

        if (!$availability) {
            $availability = (object)[
                'start_time' => '09:00',
                'end_time' => '17:00',
                'slot_duration' => 30,
                'is_available' => true,
            ];
        }

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

        if (is_object($availability) && method_exists($availability, 'getTimeSlots')) {
            $allSlots = $availability->getTimeSlots();
            return $this->processSlots($allSlots, $hourBlocks, $existingAppointments, $availability);
        }

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

        return $this->processSlots($allSlots, $hourBlocks, $existingAppointments, $availability);
    }

    private function processSlots($allSlots, $hourBlocks, $existingAppointments, $availability)
    {
        $processedSlots = [];
        foreach ($allSlots as $slot) {
            $isBlocked = false;
            $isBooked = false;
            $slotStartMinutes = $this->timeToMinutes($slot['start']);
            $slotEndMinutes = $this->timeToMinutes($slot['end']);
            
            foreach ($hourBlocks as $block) {
                $blockStartMinutes = $this->timeToMinutes($block['start']);
                $blockEndMinutes = $this->timeToMinutes($block['end']);
                if ($slotStartMinutes < $blockEndMinutes && $slotEndMinutes > $blockStartMinutes) {
                    $isBlocked = true;
                    break;
                }
            }
            
            foreach ($existingAppointments as $booked) {
                $bookedStartMinutes = $this->timeToMinutes($booked['start']);
                $bookedEndMinutes = $this->timeToMinutes($booked['end']);
                if ($slotStartMinutes < $bookedEndMinutes && $slotEndMinutes > $bookedStartMinutes) {
                    $isBooked = true;
                    break;
                }
            }
            
            $slot['is_available'] = !$isBlocked && !$isBooked;
            $slot['is_blocked'] = $isBlocked;
            $slot['is_booked'] = $isBooked;
            $processedSlots[] = $slot;
        }

        return response()->json([
            'slots' => array_values($processedSlots),
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
            if ($date->lt(now()->startOfDay())) {
                return response()->json([
                    'error' => 'Invalid date',
                    'message' => 'Cannot unblock time slots in the past'
                ], 422);
            }
            
            $deletedCount = DB::table('blocked_slots')
                ->where('doctor_id', $doctor->id)
                ->where('slot_date', $date->format('Y-m-d'))
                ->delete();
            
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
            $existing = DoctorAvailability::where('doctor_id', $doctor->id)
                ->where('type', 'specific_date')
                ->where('specific_date', $date->format('Y-m-d'))
                ->where('is_available', false)
                ->first();
            
            if ($existing) {
                return response()->json(['success' => true, 'message' => 'Day already blocked']);
            }
            
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
            $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
            $endTime = Carbon::createFromFormat('H:i', $validated['end_time']);
            $slotDate = $date->format('Y-m-d');
            $blockedCount = 0;
            $current = $startTime->copy();
            
            while ($current->copy()->addMinutes(30)->lte($endTime)) {
                $slotStart = $current->format('H:i');
                $slotEnd = $current->copy()->addMinutes(30)->format('H:i');
                
                if (!DB::table('blocked_slots')
                    ->where('doctor_id', $doctor->id)
                    ->where('slot_date', $slotDate)
                    ->where('slot_start_time', $slotStart)
                    ->where('slot_end_time', $slotEnd)
                    ->exists()) {
                    DB::table('blocked_slots')->insert([
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
                return response()->json(['error' => 'Unauthorized', 'message' => 'Please log in'], 401);
            }
            
            if (!method_exists($doctor, 'isDoctor')) {
                if ($doctor->role !== 'doctor') {
                    return response()->json(['error' => 'Unauthorized', 'message' => 'Only doctors can access this'], 403);
                }
            } elseif (!$doctor->isDoctor()) {
                return response()->json(['error' => 'Unauthorized', 'message' => 'Only doctors can access this'], 403);
            }

            $request->validate([
                'date' => ['required', 'date'],
            ]);

            try {
                $date = Carbon::parse($request->date);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format',
                    'message' => 'Please provide a valid date'
                ], 422);
            }
            
            $dayOfWeek = $date->dayOfWeek;
            $dateString = $date->format('Y-m-d');

            if (!Schema::hasTable('doctor_availabilities')) {
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
                    return [
                        'id' => $block->id,
                        'type' => 'blocked_slot',
                        'start_time' => $this->normalizeTime($block->slot_start_time),
                        'end_time' => $this->normalizeTime($block->slot_end_time),
                        'is_available' => false,
                        'notes' => $block->notes ?? 'Blocked',
                    ];
                })
                ->values();
            
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
            
            $availabilities = $blockedSlots
                ->merge($specificDateEntries)
                ->merge($dateRangeEntries)
                ->merge($weeklyEntries)
                ->map(function($avail) {
                    try {
                        if (is_array($avail)) {
                            return [
                                'id' => $avail['id'] ?? null,
                                'type' => $avail['type'] ?? 'blocked_slot',
                                'start_time' => $this->normalizeTime($avail['start_time'] ?? null),
                                'end_time' => $this->normalizeTime($avail['end_time'] ?? null),
                                'is_available' => (bool) ($avail['is_available'] ?? false),
                                'notes' => $avail['notes'] ?? null,
                            ];
                        }
                        
                        /** @var \App\Models\DoctorAvailability $avail */
                        return [
                            'id' => $avail->id ?? null,
                            'type' => $avail->type ?? 'unknown',
                            'start_time' => $this->normalizeTime($avail->start_time ?? null),
                            'end_time' => $this->normalizeTime($avail->end_time ?? null),
                            'is_available' => (bool) ($avail->is_available ?? true),
                            'notes' => $avail->notes ?? null,
                        ];
                    } catch (\Exception $e) {
                        \Log::error('Error mapping availability: ' . $e->getMessage());
                        return null;
                    }
                })
                ->filter(function($avail) {
                    return $avail !== null && $avail['start_time'] !== null && $avail['end_time'] !== null;
                })
                ->values();

            return response()->json([
                'availabilities' => $availabilities->toArray()
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
        if (!is_string($timeString)) {
            return 0;
        }
        $parts = explode(':', $timeString);
        return (int)$parts[0] * 60 + (int)($parts[1] ?? 0);
    }

    private function normalizeTime($time)
    {
        if (!$time) {
            return null;
        }
        
        if (is_string($time)) {
            return strlen($time) > 5 ? substr($time, 0, 5) : $time;
        }
        
        $timeStr = (string) $time;
        return strlen($timeStr) > 5 ? substr($timeStr, 0, 5) : $timeStr;
    }
}
