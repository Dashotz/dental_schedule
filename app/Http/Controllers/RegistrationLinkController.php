<?php

namespace App\Http\Controllers;

use App\Models\RegistrationLink;
use App\Traits\UsesSubdomainViews;
use Illuminate\Http\Request;

class RegistrationLinkController extends Controller
{
    use UsesSubdomainViews;
    public function showRegistrationForm($token)
    {
        // Security: Validate token format first
        if (!RegistrationLink::isValidTokenFormat($token)) {
            \Log::warning('Invalid registration token format attempted', [
                'token_length' => strlen($token),
                'ip' => request()->ip(),
            ]);
            abort(404, 'Registration link not found or expired.');
        }

        $registrationLink = RegistrationLink::where('token', $token)
            ->where('is_active', true)
            ->first();

        // Guard: Check if link exists
        if (!$registrationLink) {
            \Log::warning('Registration link not found', [
                'token_prefix' => substr($token, 0, 8) . '...',
                'ip' => request()->ip(),
            ]);
            abort(404, 'Registration link not found or expired.');
        }

        // Guard: Check if link is usable
        if (!$registrationLink->isUsable()) {
            \Log::info('Registration link access denied - not usable', [
                'link_id' => $registrationLink->id,
                'is_active' => $registrationLink->is_active,
                'used_count' => $registrationLink->used_count,
                'max_uses' => $registrationLink->max_uses,
                'expires_at' => $registrationLink->expires_at,
                'ip' => request()->ip(),
            ]);
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
        $doctors = \App\Models\User::where('is_active', true)
            ->where('is_active', true)
            ->get();

        return $this->subdomainView('register', compact('registrationLink', 'doctors'));
    }
}
