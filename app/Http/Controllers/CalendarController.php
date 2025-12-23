<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get all appointments for the month
        $appointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->with(['patient', 'doctor'])
            ->get()
            ->groupBy(function($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            });
        
        // Get availability data for doctors (if logged in as doctor)
        $availabilityData = [];
        if (auth()->check() && auth()->user()->isDoctor()) {
            $doctorId = auth()->id();
            
            // Get all availability entries for this month
            $availabilities = DoctorAvailability::where('doctor_id', $doctorId)
                ->where(function($query) use ($startDate, $endDate) {
                    $query->where(function($q) use ($startDate, $endDate) {
                        // Weekly schedules
                        $q->where('type', 'weekly');
                    })->orWhere(function($q) use ($startDate, $endDate) {
                        // Specific dates in this month
                        $q->where('type', 'specific_date')
                          ->whereBetween('specific_date', [$startDate, $endDate]);
                    })->orWhere(function($q) use ($startDate, $endDate) {
                        // Date ranges that overlap with this month
                        $q->where('type', 'date_range')
                          ->where(function($subQ) use ($startDate, $endDate) {
                              $subQ->whereBetween('start_date', [$startDate, $endDate])
                                   ->orWhereBetween('end_date', [$startDate, $endDate])
                                   ->orWhere(function($r) use ($startDate, $endDate) {
                                       $r->where('start_date', '<=', $startDate)
                                         ->where('end_date', '>=', $endDate);
                                   });
                          });
                    });
                })
                ->get();
            
            // Group by date
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $dateKey = $currentDate->format('Y-m-d');
                $dayOfWeek = $currentDate->dayOfWeek;
                
                $dayAvailabilities = $availabilities->filter(function($avail) use ($currentDate, $dayOfWeek) {
                    if ($avail->type === 'weekly' && $avail->day_of_week == $dayOfWeek) {
                        return true;
                    }
                    if ($avail->type === 'specific_date' && $avail->specific_date && $avail->specific_date->isSameDay($currentDate)) {
                        return true;
                    }
                    if ($avail->type === 'date_range' && $avail->start_date && $avail->end_date) {
                        return $currentDate->gte($avail->start_date) && $currentDate->lte($avail->end_date);
                    }
                    return false;
                });
                
                $availabilityData[$dateKey] = $dayAvailabilities;
                
                $currentDate->addDay();
            }
        }
        
        // Calendar data
        $calendar = [];
        $currentDate = $startDate->copy();
        
        // Add empty cells for days before month starts
        $firstDayOfWeek = $startDate->dayOfWeek;
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $calendar[] = null;
        }
        
        // Add days of the month
        while ($currentDate->lte($endDate)) {
            $dateKey = $currentDate->format('Y-m-d');
            $dayAppointments = $appointments->get($dateKey, collect());
            $dayAvailabilities = $availabilityData[$dateKey] ?? collect();
            
            // Check if day is blocked (only full-day blocks, not partial hour blocks)
            $isBlocked = $dayAvailabilities->contains(function($avail) {
                if ($avail->is_available === false) {
                    // Only consider it a full-day block if it's 00:00 to 23:59
                    $startTime = $avail->start_time;
                    $endTime = $avail->end_time;
                    
                    // Normalize time format
                    if (strlen($startTime) > 5) {
                        $startTime = substr($startTime, 0, 5);
                    }
                    if (strlen($endTime) > 5) {
                        $endTime = substr($endTime, 0, 5);
                    }
                    
                    // Only full-day blocks (00:00 to 23:59) mark the entire day as blocked
                    return ($startTime === '00:00' && $endTime === '23:59');
                }
                return false;
            });
            
            $calendar[] = [
                'date' => $currentDate->copy(),
                'appointments' => $dayAppointments,
                'count' => $dayAppointments->count(),
                'isToday' => $currentDate->isToday(),
                'isBlocked' => $isBlocked,
                'availabilities' => $dayAvailabilities,
            ];
            
            $currentDate->addDay();
        }
        
        // Previous and next month
        $prevMonth = $startDate->copy()->subMonth();
        $nextMonth = $startDate->copy()->addMonth();
        
        return view('calendar.index', compact(
            'calendar',
            'startDate',
            'year',
            'month',
            'prevMonth',
            'nextMonth'
        ));
    }
}
