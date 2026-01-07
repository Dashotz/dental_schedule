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
        // Optimize: Eager load subscriptions and filter active ones
        $subdomains = Subdomain::with(['subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->where('end_date', '>=', now())
                  ->latest('end_date')
                  ->limit(1); // Only need the latest active subscription
        }])->latest()->paginate(15);
        
        return view('main-site.admin.subdomains.index', compact('subdomains'));
    }

    public function create()
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('main-site.admin.subdomains.partials.create-modal')->render()
            ]);
        }
        return view('main-site.admin.subdomains.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subdomain' => ['required', 'string', 'max:63', 'min:3', 'unique:subdomains,subdomain', 'regex:/^[a-z0-9\-]+$/', 'not_in:www,mail,ftp,localhost,admin,api,app,test,dev,staging,production'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $subdomain = Subdomain::create($validated);
        
        // Clear subdomains cache when new subdomain is created
        cache()->forget('subdomains_list');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subdomain created successfully.',
                'subdomain' => $subdomain
            ]);
        }

        return redirect()->route('admin.subdomains.index')
            ->with('success', 'Subdomain created successfully.');
    }

    public function show(Subdomain $subdomain)
    {
        $subdomain->load(['registrationLinks']);
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('main-site.admin.subdomains.partials.show-modal', compact('subdomain'))->render()
            ]);
        }
        
        // Redirect to index for non-AJAX requests
        return redirect()->route('admin.subdomains.index');
    }

    public function edit(Subdomain $subdomain)
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('main-site.admin.subdomains.partials.edit-form', compact('subdomain'))->render()
            ]);
        }
        return view('main-site.admin.subdomains.edit', compact('subdomain'));
    }

    public function update(Request $request, Subdomain $subdomain)
    {
        $validated = $request->validate([
            'subdomain' => ['required', 'string', 'max:63', 'min:3', 'unique:subdomains,subdomain,' . $subdomain->id, 'regex:/^[a-z0-9\-]+$/', 'not_in:www,mail,ftp,localhost,admin,api,app,test,dev,staging,production'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $subdomain->update($validated);
        
        // Clear subdomains cache when subdomain is updated
        cache()->forget('subdomains_list');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subdomain updated successfully.',
                'subdomain' => $subdomain->fresh()
            ]);
        }

        return redirect()->route('admin.subdomains.index')
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

        // Ensure expiration is at least 1 day from now (security: prevent very short-lived links)
        $minExpiration = now()->addDay();
        $expiresAt = $subscriptionEndDate->gt($minExpiration) ? $subscriptionEndDate : $minExpiration;

        $registrationLink = $subdomain->registrationLinks()->create([
            'created_by' => auth('admin')->id(),
            'token' => $token,
            'link' => $link,
            'max_uses' => 0, // Unlimited uses (can be configured per subdomain)
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);

        // Log registration link creation for security audit
        \Log::info('Registration link created', [
            'subdomain_id' => $subdomain->id,
            'created_by' => auth('admin')->id(),
            'expires_at' => $expiresAt,
            'ip' => $request->ip(),
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
        
        // Clear subdomains cache when subdomain is deleted
        cache()->forget('subdomains_list');

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
