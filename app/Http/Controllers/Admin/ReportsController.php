<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use App\Models\Subscription;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function reports()
    {
        // Revenue over time (last 12 months)
        $revenueData = Subscription::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Subscription growth
        $subscriptionGrowth = Subscription::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Subdomain growth
        $subdomainGrowth = Subdomain::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Revenue by plan
        $revenueByPlan = Subscription::selectRaw('plan_name, SUM(amount) as total')
            ->groupBy('plan_name')
            ->get();

        // Revenue by billing cycle
        $revenueByBillingCycle = Subscription::selectRaw('billing_cycle, SUM(amount) as total')
            ->groupBy('billing_cycle')
            ->get();

        // Monthly revenue breakdown
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Subscription::whereMonth('last_payment_date', $month->month)
                ->whereYear('last_payment_date', $month->year)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }

        return view('admin.reports.index', compact(
            'revenueData',
            'subscriptionGrowth',
            'subdomainGrowth',
            'revenueByPlan',
            'revenueByBillingCycle',
            'monthlyRevenue'
        ));
    }

    public function insights()
    {
        // Subscription status distribution
        $subscriptionStatus = Subscription::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Plan distribution
        $planDistribution = Subscription::selectRaw('plan_name, COUNT(*) as count')
            ->groupBy('plan_name')
            ->get();

        // Billing cycle distribution
        $billingCycleDistribution = Subscription::selectRaw('billing_cycle, COUNT(*) as count')
            ->groupBy('billing_cycle')
            ->get();

        // Active vs Inactive subdomains
        $subdomainStatus = Subdomain::selectRaw('is_active, COUNT(*) as count')
            ->groupBy('is_active')
            ->get();

        // Top revenue generating subdomains
        $topRevenueSubdomains = Subdomain::withSum('subscriptions', 'amount')
            ->orderBy('subscriptions_sum_amount', 'desc')
            ->limit(10)
            ->get();

        // Subscription renewal rate (active vs expired)
        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('status', 'active')
            ->where('end_date', '>=', now())
            ->count();
        $renewalRate = $totalSubscriptions > 0 ? ($activeSubscriptions / $totalSubscriptions) * 100 : 0;

        // Average subscription value
        $avgSubscriptionValue = Subscription::avg('amount');

        // Revenue trends (last 6 months)
        $revenueTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Subscription::whereMonth('last_payment_date', $month->month)
                ->whereYear('last_payment_date', $month->year)
                ->sum('amount');
            $revenueTrends[] = [
                'month' => $month->format('M'),
                'revenue' => $revenue
            ];
        }

        return view('admin.insights.index', compact(
            'subscriptionStatus',
            'planDistribution',
            'billingCycleDistribution',
            'subdomainStatus',
            'topRevenueSubdomains',
            'renewalRate',
            'avgSubscriptionValue',
            'revenueTrends'
        ));
    }
}
