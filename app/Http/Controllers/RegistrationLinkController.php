<?php

namespace App\Http\Controllers;

use App\Models\RegistrationLink;
use Illuminate\Http\Request;

class RegistrationLinkController extends Controller
{
    public function showRegistrationForm($token)
    {
        $registrationLink = RegistrationLink::where('token', $token)
            ->where('is_active', true)
            ->first();

        // Guard: Check if link exists
        if (!$registrationLink) {
            abort(404, 'Registration link not found or expired.');
        }

        // Guard: Check if link is usable
        if (!$registrationLink->isUsable()) {
            abort(403, 'This registration link has expired or reached its usage limit.');
        }

        // Guard: Check if subdomain is active
        if ($registrationLink->subdomain && !$registrationLink->subdomain->is_active) {
            abort(403, 'This dental clinic website is currently disabled.');
        }

        // Guard: Check if subdomain has active subscription
        if ($registrationLink->subdomain) {
            $subdomain = $registrationLink->subdomain;
            $hasActiveSubscription = $subdomain->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>=', now())
                ->exists();
            
            if (!$hasActiveSubscription) {
                abort(403, 'This dental clinic subscription has expired. Please contact the clinic.');
            }
        }

        // Get available doctors for this subdomain (or all active doctors if no subdomain)
        $doctors = \App\Models\User::where('role', 'doctor')
            ->where('is_active', true)
            ->get();

        return view('patient.register', compact('registrationLink', 'doctors'));
    }
}
