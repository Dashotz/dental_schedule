<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubdomainController extends Controller
{
    public function index()
    {
        $subdomains = Subdomain::with(['subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->where('end_date', '>=', now())
                  ->latest('end_date');
        }])->latest()->paginate(15);
        return view('admin.subdomains.index', compact('subdomains'));
    }

    public function create()
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.subdomains.modals.create')->render()
            ]);
        }
        return view('admin.subdomains.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subdomain' => ['required', 'string', 'max:255', 'unique:subdomains,subdomain', 'regex:/^[a-z0-9\-]+$/'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $subdomain = Subdomain::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subdomain created successfully.',
                'subdomain' => $subdomain
            ]);
        }

        return redirect()->route('admin.subdomains.show', $subdomain)
            ->with('success', 'Subdomain created successfully.');
    }

    public function show(Subdomain $subdomain)
    {
        $subdomain->load(['registrationLinks']);
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.subdomains.modals.show', compact('subdomain'))->render()
            ]);
        }
        
        return view('admin.subdomains.show', compact('subdomain'));
    }

    public function edit(Subdomain $subdomain)
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.subdomains.modals.edit-form', compact('subdomain'))->render()
            ]);
        }
        return view('admin.subdomains.edit', compact('subdomain'));
    }

    public function update(Request $request, Subdomain $subdomain)
    {
        $validated = $request->validate([
            'subdomain' => ['required', 'string', 'max:255', 'unique:subdomains,subdomain,' . $subdomain->id, 'regex:/^[a-z0-9\-]+$/'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $subdomain->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subdomain updated successfully.',
                'subdomain' => $subdomain->fresh()
            ]);
        }

        return redirect()->route('admin.subdomains.show', $subdomain)
            ->with('success', 'Subdomain updated successfully.');
    }

    public function toggleStatus(Subdomain $subdomain)
    {
        $subdomain->is_active = !$subdomain->is_active;
        $subdomain->save();

        $message = $subdomain->is_active ? 'Subdomain enabled successfully.' : 'Subdomain disabled successfully.';
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'is_active' => $subdomain->is_active
        ]);
    }

    public function generateRegistrationLink(Subdomain $subdomain, Request $request)
    {
        // Get the active subscription end date
        $subscriptionEndDate = null;
        $activeSubscription = $subdomain->activeSubscription();
        
        if ($activeSubscription) {
            $subscriptionEndDate = $activeSubscription->end_date;
        } else {
            // If no active subscription, check if there's a subscription_expires_at on subdomain
            if ($subdomain->subscription_expires_at) {
                $subscriptionEndDate = $subdomain->subscription_expires_at;
            }
        }

        // Guard: Check if subdomain has an active subscription
        if (!$subscriptionEndDate || $subscriptionEndDate->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot generate link: Subdomain does not have an active subscription.'
            ], 400);
        }

        // Deactivate any existing active registration links for this subdomain
        $subdomain->registrationLinks()
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $token = \App\Models\RegistrationLink::generateToken();
        $link = url('/register/' . $token);

        $registrationLink = $subdomain->registrationLinks()->create([
            'created_by' => auth()->id(),
            'token' => $token,
            'link' => $link,
            'max_uses' => 0, // Unlimited uses
            'expires_at' => $subscriptionEndDate, // Expires when subscription ends
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'link' => $link,
            'message' => 'Registration link generated successfully. Previous link has been deactivated. Link expires when subscription ends.'
        ]);
    }

    public function destroy(Subdomain $subdomain)
    {
        // Delete related registration links
        $subdomain->registrationLinks()->delete();
        
        // Delete related subscriptions
        $subdomain->subscriptions()->delete();
        
        // Delete the subdomain
        $subdomain->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subdomain deleted successfully.'
            ]);
        }

        return redirect()->route('admin.subdomains.index')
            ->with('success', 'Subdomain deleted successfully.');
    }
}
