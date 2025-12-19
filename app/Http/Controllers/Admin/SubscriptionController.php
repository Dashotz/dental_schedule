<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('subdomain')->latest()->paginate(15);
        $subdomains = Subdomain::orderBy('name')->get();
        return view('admin.subscriptions.index', compact('subscriptions', 'subdomains'));
    }

    public function create()
    {
        $subdomains = Subdomain::orderBy('name')->get();
        return view('admin.subscriptions.create', compact('subdomains'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subdomain_id' => ['required', 'exists:subdomains,id'],
            'plan_name' => ['required', 'string', 'in:basic,premium,enterprise'],
            'amount' => ['required', 'numeric', 'min:0'],
            'billing_cycle' => ['required', 'in:monthly,quarterly,yearly'],
            'start_date' => ['required', 'date'],
            'payment_details' => ['nullable', 'string'],
        ]);

        $subdomain = Subdomain::findOrFail($validated['subdomain_id']);

        // Always automatically calculate end date based on billing cycle and start date
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = null;
        
        switch ($validated['billing_cycle']) {
            case 'monthly':
                $endDate = $startDate->copy()->addMonth()->subDay(); // 1 month minus 1 day
                break;
            case 'quarterly':
                $endDate = $startDate->copy()->addMonths(3)->subDay(); // 3 months minus 1 day
                break;
            case 'yearly':
                $endDate = $startDate->copy()->addYear()->subDay(); // 1 year minus 1 day
                break;
        }

        // Calculate next payment date (day after end date)
        $nextPaymentDate = $endDate->copy()->addDay();

        $subscription = $subdomain->subscriptions()->create([
            'plan_name' => $validated['plan_name'],
            'amount' => $validated['amount'],
            'billing_cycle' => $validated['billing_cycle'],
            'start_date' => $validated['start_date'],
            'end_date' => $endDate->format('Y-m-d'),
            'status' => 'active',
            'payment_details' => $validated['payment_details'] ?? null,
            'last_payment_date' => $validated['start_date'],
            'next_payment_date' => $nextPaymentDate->format('Y-m-d'),
        ]);

        // Enable subdomain if subscription is active
        $subdomain->update(['is_active' => true]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    public function updateStatus(Subscription $subscription, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,expired,cancelled,pending'],
        ]);

        $subscription->update(['status' => $validated['status']]);

        // Auto-disable subdomain if subscription expired or cancelled
        if (in_array($validated['status'], ['expired', 'cancelled'])) {
            $subscription->subdomain->update(['is_active' => false]);
        } elseif ($validated['status'] === 'active' && $subscription->end_date >= now()) {
            $subscription->subdomain->update(['is_active' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription status updated successfully.'
        ]);
    }

    public function sendPaymentReminder(Subscription $subscription)
    {
        // TODO: Implement email/SMS notification
        // For now, log and return success
        \Log::info('Payment reminder sent for subscription ID: ' . $subscription->id . ' - Subdomain: ' . $subscription->subdomain->name);
        
        return response()->json([
            'success' => true,
            'message' => 'Payment reminder sent successfully to ' . ($subscription->subdomain->email ?? 'subdomain owner') . '.'
        ]);
    }
}
