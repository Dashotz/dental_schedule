<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        // Today's appointments
        $todayAppointments = Appointment::whereDate('appointment_date', $today)
            ->with('patient')
            ->orderBy('appointment_date')
            ->get();

        // This week's appointments
        $weekAppointments = Appointment::whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
            ->with('patient')
            ->orderBy('appointment_date')
            ->get();

        // Upcoming appointments (next 7 days)
        $upcomingAppointments = Appointment::where('appointment_date', '>', $today)
            ->where('appointment_date', '<=', $today->copy()->addDays(7))
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->with('patient')
            ->orderBy('appointment_date')
            ->limit(10)
            ->get();

        // Statistics
        $totalPatients = Patient::count();
        $totalAppointments = Appointment::count();
        $todayAppointmentsCount = $todayAppointments->count();
        $pendingAppointments = Appointment::whereIn('status', ['scheduled', 'confirmed'])->count();

        // Recent patients
        $recentPatients = Patient::latest()->limit(5)->get();

        return view('dashboard', compact(
            'todayAppointments',
            'weekAppointments',
            'upcomingAppointments',
            'totalPatients',
            'totalAppointments',
            'todayAppointmentsCount',
            'pendingAppointments',
            'recentPatients'
        ));
    }
}
