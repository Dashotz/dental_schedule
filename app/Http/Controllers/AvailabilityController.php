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

        // Check for blocked dates (only blocks, not lack of availability)
        $blocked = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where(function($query) use ($date, $dayOfWeek) {
                $query->where(function($q) use ($date) {
                    $q->where('type', 'specific_date')
                      ->where('specific_date', $date->format('Y-m-d'))
                      ->where('is_available', false);
                })->orWhere(function($q) use ($date) {
                    $q->where('type', 'date_range')
                      ->where('start_date', '<=', $date)
                      ->where('end_date', '>=', $date)
                      ->where('is_available', false);
                })->orWhere(function($q) use ($dayOfWeek) {
                    $q->where('type', 'weekly')
                      ->where('day_of_week', $dayOfWeek)
                      ->where('is_available', false);
                });
            })
            ->exists();

        if ($blocked) {
            return response()->json(['slots' => []]);
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

        // Filter out booked slots
        $availableSlots = array_filter($allSlots, function($slot) use ($existingAppointments) {
            foreach ($existingAppointments as $booked) {
                if (($slot['start'] >= $booked['start'] && $slot['start'] < $booked['end']) ||
                    ($slot['end'] > $booked['start'] && $slot['end'] <= $booked['end']) ||
                    ($slot['start'] <= $booked['start'] && $slot['end'] >= $booked['end'])) {
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
            // Delete all availability entries for this specific date
            DoctorAvailability::where('doctor_id', $doctor->id)
                ->where(function($query) use ($date) {
                    $query->where(function($q) use ($date) {
                        $q->where('type', 'specific_date')
                          ->where('specific_date', $date->format('Y-m-d'));
                    })->orWhere(function($q) use ($date) {
                        $q->where('type', 'date_range')
                          ->where('start_date', '<=', $date)
                          ->where('end_date', '>=', $date)
                          ->where('is_available', false);
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
        $doctor = auth()->user();
        
        if (!$doctor->isDoctor()) {
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
                return [
                    'id' => $avail->id,
                    'type' => $avail->type,
                    'start_time' => substr($avail->start_time, 0, 5),
                    'end_time' => substr($avail->end_time, 0, 5),
                    'is_available' => $avail->is_available,
                    'notes' => $avail->notes,
                ];
            });

        return response()->json(['availabilities' => $availabilities]);
    }
}
