<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subdomain;
use Symfony\Component\HttpFoundation\Response;

class CheckSubdomainStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parentDomain = config('subdomain.parent_domain');
        $baseDomain = config('subdomain.base_domain');
        
        // Allow admin routes to bypass subdomain checks (admins can access from anywhere)
        if ($request->is('admin/*') && auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }
        
        // If accessing parent domain, allow access
        if ($host === $parentDomain) {
            return $next($request);
        }
        
        // Extract subdomain from host (e.g., dental-imus from dental-imus.helioho.st)
        $subdomainName = str_replace('.' . $baseDomain, '', $host);
        
        // If it's the base domain without subdomain, allow (for main site)
        if ($host === $baseDomain || $subdomainName === $host) {
            return $next($request);
        }
        
        // Find the subdomain in database
        $subdomain = Subdomain::where('subdomain', $subdomainName)->first();
        
        // If subdomain doesn't exist, show not found
        if (!$subdomain) {
            return response()->view('errors.subdomain-not-found', [
                'subdomain' => $subdomainName
            ], 404);
        }
        
        // Check if subdomain is active
        if (!$subdomain->is_active) {
            return response()->view('subscription-required', [
                'subdomain' => $subdomain,
                'reason' => 'disabled'
            ], 403);
        }
        
        // Check if subdomain has active subscription
        if (!$subdomain->isSubscriptionActive()) {
            return response()->view('subscription-required', [
                'subdomain' => $subdomain,
                'reason' => 'subscription_expired'
            ], 403);
        }
        
        // Store subdomain in request for later use
        $request->attributes->set('current_subdomain', $subdomain);
        
        return $next($request);
    }
}
