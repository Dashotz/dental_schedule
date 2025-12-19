<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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
            
            $calendar[] = [
                'date' => $currentDate->copy(),
                'appointments' => $dayAppointments,
                'count' => $dayAppointments->count(),
                'isToday' => $currentDate->isToday(),
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
