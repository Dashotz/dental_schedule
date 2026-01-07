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
        // Optimize: Get subdomain counts in a single query
        $subdomainStats = Subdomain::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive
        ')->first();
        
        $totalSubdomains = $subdomainStats->total;
        $activeSubdomains = $subdomainStats->active;
        $inactiveSubdomains = $subdomainStats->inactive;

        // Optimize: Get subscription statistics in fewer queries
        $now = now();
        $sevenDaysFromNow = $now->copy()->addDays(7);
        $currentMonth = $now->month;
        $currentYear = $now->year;
        
        $subscriptionStats = Subscription::selectRaw('
            SUM(CASE WHEN status = "active" AND end_date >= ? THEN 1 ELSE 0 END) as active_count,
            SUM(CASE WHEN status = "active" AND end_date >= ? AND end_date <= ? THEN 1 ELSE 0 END) as expiring_count,
            SUM(CASE WHEN status = "expired" OR (status = "active" AND end_date < ?) THEN 1 ELSE 0 END) as expired_count,
            SUM(CASE WHEN status = "active" THEN amount ELSE 0 END) as total_revenue,
            SUM(CASE WHEN status = "active" AND MONTH(last_payment_date) = ? AND YEAR(last_payment_date) = ? THEN amount ELSE 0 END) as monthly_revenue
        ', [$now, $now, $sevenDaysFromNow, $now, $currentMonth, $currentYear])->first();
        
        $activeSubscriptions = $subscriptionStats->active_count ?? 0;
        $expiringSubscriptions = $subscriptionStats->expiring_count ?? 0;
        $expiredSubscriptions = $subscriptionStats->expired_count ?? 0;
        $totalRevenue = $subscriptionStats->total_revenue ?? 0;
        $monthlyRevenue = $subscriptionStats->monthly_revenue ?? 0;

        // Recent subdomains with eager loading
        $recentSubdomains = Subdomain::latest()->limit(5)->get();

        // Optimize: Get expiring subdomains with join instead of whereHas
        $expiringSoon = Subdomain::join('subscriptions', 'subdomains.id', '=', 'subscriptions.subdomain_id')
            ->where('subscriptions.status', 'active')
            ->where('subscriptions.end_date', '>=', $now)
            ->where('subscriptions.end_date', '<=', $sevenDaysFromNow)
            ->select('subdomains.*')
            ->distinct()
            ->get();

        // Analytics data - already optimized with groupBy
        $subscriptionsByPlan = Subscription::selectRaw('plan_name, count(*) as count')
            ->groupBy('plan_name')
            ->get();

        $subscriptionsByStatus = Subscription::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        return view('main-site.admin.dashboard', compact(
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
