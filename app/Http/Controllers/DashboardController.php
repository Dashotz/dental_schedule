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
        $sevenDaysFromNow = $today->copy()->addDays(7);

        // Optimize: Get all appointments in date ranges in fewer queries
        // Get today's and week's appointments in one query
        $appointmentsInRange = Appointment::whereBetween('appointment_date', [$startOfWeek, $sevenDaysFromNow])
            ->with('patient')
            ->orderBy('appointment_date')
            ->get();
        
        // Filter in memory (faster than multiple DB queries)
        $todayAppointments = $appointmentsInRange->filter(function($apt) use ($today) {
            return $apt->appointment_date->isSameDay($today);
        })->values();
        
        $weekAppointments = $appointmentsInRange->filter(function($apt) use ($startOfWeek, $endOfWeek) {
            return $apt->appointment_date->between($startOfWeek, $endOfWeek);
        })->values();
        
        $upcomingAppointments = $appointmentsInRange->filter(function($apt) use ($today, $sevenDaysFromNow) {
            return $apt->appointment_date->gt($today) 
                && $apt->appointment_date->lte($sevenDaysFromNow)
                && in_array($apt->status, ['scheduled', 'confirmed']);
        })->take(10)->values();

        // Optimize: Get statistics in single queries
        $stats = Appointment::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN DATE(appointment_date) = ? THEN 1 ELSE 0 END) as today_count,
            SUM(CASE WHEN status IN ("scheduled", "confirmed") THEN 1 ELSE 0 END) as pending_count
        ', [$today])->first();
        
        $totalAppointments = $stats->total ?? 0;
        $todayAppointmentsCount = $stats->today_count ?? 0;
        $pendingAppointments = $stats->pending_count ?? 0;
        $totalPatients = Patient::count(); // Keep separate as it's a different table

        // Recent patients
        $recentPatients = Patient::latest()->limit(5)->get();

        return view('subdomain-template.dashboard', compact(
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
