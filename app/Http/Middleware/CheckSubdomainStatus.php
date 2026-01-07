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
        $port = $request->getPort();
        $parentDomain = config('subdomain.parent_domain');
        $baseDomain = config('subdomain.base_domain');
        
        // Block admin access on subdomain-specific ports (10000+)
        // Admins should only access admin panel on port 9000
        if ($port >= 10000 && auth('admin')->check()) {
            abort(403, 'Admin access is not allowed on subdomain ports. Please access the admin panel on port 9000.');
        }
        
        // Allow admin routes to bypass subdomain checks only on port 9000
        if ($request->is('admin/*') && auth('admin')->check() && $port == 9000) {
            return $next($request);
        }
        
        // If accessing parent domain, allow access
        if ($host === $parentDomain) {
            return $next($request);
        }
        
        // Check if accessing via port (subdomain-specific ports: 10000+)
        if ($port >= 10000) {
            $subdomain = Subdomain::where('port', $port)->first();
            
            if ($subdomain) {
                // Check if subdomain is active
                if (!$subdomain->is_active) {
                    $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
                    return response()->view($viewPath . '.subscription-required', [
                        'subdomain' => $subdomain,
                        'reason' => 'disabled'
                    ], 403);
                }
                
                // Check if subdomain has active subscription
                if (!$subdomain->isSubscriptionActive()) {
                    $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
                    return response()->view($viewPath . '.subscription-required', [
                        'subdomain' => $subdomain,
                        'reason' => 'subscription_expired'
                    ], 403);
                }
                
                // Store subdomain in request for later use
                $request->attributes->set('current_subdomain', $subdomain);
                
                // Set view path for subdomain-specific views
                $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
                $request->attributes->set('subdomain_view_path', $viewPath);
                
                return $next($request);
            }
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
            $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
            return response()->view($viewPath . '.subscription-required', [
                'subdomain' => $subdomain,
                'reason' => 'disabled'
            ], 403);
        }
        
        // Check if subdomain has active subscription
        if (!$subdomain->isSubscriptionActive()) {
            $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
            return response()->view($viewPath . '.subscription-required', [
                'subdomain' => $subdomain,
                'reason' => 'subscription_expired'
            ], 403);
        }
        
        // Store subdomain in request for later use
        $request->attributes->set('current_subdomain', $subdomain);
        
        // Set view path for subdomain-specific views
        $viewPath = \App\Services\SubdomainTemplateService::getViewPath($subdomain);
        $request->attributes->set('subdomain_view_path', $viewPath);
        
        return $next($request);
    }
}
