<?php

namespace App\Http\Controllers;

use App\Models\DoctorAvailability;
use App\Models\User;
use Illuminate\Http\Request;
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
            'doctor_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
        ]);

        $doctor = User::findOrFail($request->doctor_id);
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        // Get weekly availability for this day
        $weeklyAvailability = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('type', 'weekly')
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        // Get specific date overrides
        $specificAvailability = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('type', 'specific_date')
            ->where('specific_date', $date->format('Y-m-d'))
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

        // Filter out booked slots and blocked hours
        $availableSlots = array_filter($allSlots, function($slot) use ($existingAppointments, $hourBlocks) {
            // Check against existing appointments
            foreach ($existingAppointments as $booked) {
                if (($slot['start'] >= $booked['start'] && $slot['start'] < $booked['end']) ||
                    ($slot['end'] > $booked['start'] && $slot['end'] <= $booked['end']) ||
                    ($slot['start'] <= $booked['start'] && $slot['end'] >= $booked['end'])) {
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

        return response()->json([
            'slots' => array_values($availableSlots),
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
            // Delete all blocked availability entries for this specific date (both full day and hour blocks)
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
            
            return response()->json(['success' => true, 'message' => 'Date unblocked successfully']);
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
            // Create block for specific hours
            DoctorAvailability::create([
                'doctor_id' => $doctor->id,
                'type' => 'specific_date',
                'specific_date' => $date->format('Y-m-d'),
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'slot_duration' => 30,
                'is_available' => false,
                'notes' => 'Blocked hours',
            ]);
            
            return response()->json(['success' => true, 'message' => 'Hours blocked successfully']);
        }
        
        return response()->json(['error' => 'Invalid action'], 400);
    }

    public function getDateAvailability(Request $request)
    {
        try {
            $doctor = auth()->user();
            
            if (!$doctor || !$doctor->isDoctor()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $request->validate([
                'date' => ['required', 'date'],
            ]);

            $date = Carbon::parse($request->date);
            $dayOfWeek = $date->dayOfWeek;

            // Get all availability entries for this date
            $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
                ->where(function($query) use ($date, $dayOfWeek) {
                    $query->where(function($q) use ($date) {
                        $q->where('type', 'specific_date')
                          ->where('specific_date', $date->format('Y-m-d'));
                    })->orWhere(function($q) use ($date) {
                        $q->where('type', 'date_range')
                          ->where('start_date', '<=', $date)
                          ->where('end_date', '>=', $date);
                    })->orWhere(function($q) use ($dayOfWeek) {
                        $q->where('type', 'weekly')
                          ->where('day_of_week', $dayOfWeek);
                    });
                })
                ->get()
                ->map(function($avail) {
                    try {
                        // Safely extract time, handling null or different formats
                        $startTime = null;
                        $endTime = null;
                        
                        if ($avail->start_time) {
                            $startTime = is_string($avail->start_time) && strlen($avail->start_time) >= 5 
                                ? substr($avail->start_time, 0, 5) 
                                : $avail->start_time;
                        }
                        
                        if ($avail->end_time) {
                            $endTime = is_string($avail->end_time) && strlen($avail->end_time) >= 5 
                                ? substr($avail->end_time, 0, 5) 
                                : $avail->end_time;
                        }
                        
                        return [
                            'id' => $avail->id,
                            'type' => $avail->type,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'is_available' => (bool) $avail->is_available,
                            'notes' => $avail->notes,
                        ];
                    } catch (\Exception $e) {
                        \Log::error('Error mapping availability: ' . $e->getMessage());
                        return null;
                    }
                })
                ->filter(function($avail) {
                    // Only return entries with valid times
                    return $avail !== null && $avail['start_time'] !== null && $avail['end_time'] !== null;
                })
                ->values();

            return response()->json(['availabilities' => $availabilities]);
        } catch (\Exception $e) {
            \Log::error('getDateAvailability error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to load availability',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
