<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use App\Models\Subscription;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total subdomains
        $totalSubdomains = Subdomain::count();
        $activeSubdomains = Subdomain::where('is_active', true)->count();
        $inactiveSubdomains = Subdomain::where('is_active', false)->count();

        // Subscription statistics
        $activeSubscriptions = Subscription::where('status', 'active')
            ->where('end_date', '>=', now())
            ->count();
        
        $expiringSubscriptions = Subscription::where('status', 'active')
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(7))
            ->count();

        $expiredSubscriptions = Subscription::where('status', 'expired')
            ->orWhere(function($query) {
                $query->where('status', 'active')
                    ->where('end_date', '<', now());
            })
            ->count();

        // Revenue statistics
        $totalRevenue = Subscription::where('status', 'active')
            ->sum('amount');
        
        $monthlyRevenue = Subscription::where('status', 'active')
            ->whereMonth('last_payment_date', now()->month)
            ->whereYear('last_payment_date', now()->year)
            ->sum('amount');

        // Recent subdomains
        $recentSubdomains = Subdomain::latest()->limit(5)->get();

        // Subdomains with expiring subscriptions
        $expiringSoon = Subdomain::whereHas('subscriptions', function($query) {
            $query->where('status', 'active')
                ->where('end_date', '>=', now())
                ->where('end_date', '<=', now()->addDays(7));
        })->get();

        // Analytics data
        $subscriptionsByPlan = Subscription::selectRaw('plan_name, count(*) as count')
            ->groupBy('plan_name')
            ->get();

        $subscriptionsByStatus = Subscription::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact(
            'totalSubdomains',
            'activeSubdomains',
            'inactiveSubdomains',
            'activeSubscriptions',
            'expiringSubscriptions',
            'expiredSubscriptions',
            'totalRevenue',
            'monthlyRevenue',
            'recentSubdomains',
            'expiringSoon',
            'subscriptionsByPlan',
            'subscriptionsByStatus'
        ));
    }
}
