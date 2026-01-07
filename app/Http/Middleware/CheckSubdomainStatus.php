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
        $isProduction = config('app.env') === 'production';
        
        // PRODUCTION: Prioritize hostname-based routing
        if ($isProduction) {
            return $this->handleHostnameBased($request, $next, $host, $parentDomain, $baseDomain);
        }
        
        // DEVELOPMENT: Use port-based routing
        return $this->handlePortBased($request, $next, $host, $port, $parentDomain, $baseDomain);
    }
    
    /**
     * Handle hostname-based routing (for production)
     */
    protected function handleHostnameBased(Request $request, Closure $next, string $host, string $parentDomain, string $baseDomain): Response
    {
        // Allow admin routes on admin domain or main domain
        if ($request->is('admin/*')) {
            $adminDomain = env('ADMIN_DOMAIN', 'admin.' . parse_url($parentDomain, PHP_URL_HOST));
            $mainDomain = parse_url(config('app.url', $parentDomain), PHP_URL_HOST);
            
            // Allow if accessing admin domain, main domain, or host starts with 'admin.'
            if ($host === parse_url($adminDomain, PHP_URL_HOST) || 
                $host === $mainDomain || 
                str_starts_with($host, 'admin.')) {
                return $next($request);
            }
        }
        
        // If accessing parent/main domain, allow access
        if ($host === $parentDomain || $host === parse_url(config('app.url', $parentDomain), PHP_URL_HOST)) {
            return $next($request);
        }
        
        // Extract subdomain from host (e.g., clinic1 from clinic1.yourdomain.com)
        $subdomainName = $this->extractSubdomainName($host, $baseDomain);
        
        // If it's the base domain without subdomain, allow (for main site)
        if ($host === $baseDomain || $subdomainName === $host || !$subdomainName) {
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
        
        return $this->processSubdomain($request, $subdomain, $next);
    }
    
    /**
     * Handle port-based routing (for development)
     */
    protected function handlePortBased(Request $request, Closure $next, string $host, int $port, string $parentDomain, string $baseDomain): Response
    {
        // Block admin access on subdomain-specific ports (10000+)
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
                return $this->processSubdomain($request, $subdomain, $next);
            }
        }
        
        // Extract subdomain from host (fallback for development)
        $subdomainName = $this->extractSubdomainName($host, $baseDomain);
        
        // If it's the base domain without subdomain, allow (for main site)
        if ($host === $baseDomain || $subdomainName === $host || !$subdomainName) {
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
        
        return $this->processSubdomain($request, $subdomain, $next);
    }
    
    /**
     * Extract subdomain name from hostname
     */
    protected function extractSubdomainName(string $host, string $baseDomain): ?string
    {
        // Remove base domain to get subdomain
        if (str_ends_with($host, '.' . $baseDomain)) {
            $subdomainName = str_replace('.' . $baseDomain, '', $host);
            // Don't return empty string or if it's the same as host
            return $subdomainName && $subdomainName !== $host ? $subdomainName : null;
        }
        
        return null;
    }
    
    /**
     * Process subdomain (check status, set attributes, etc.)
     */
    protected function processSubdomain(Request $request, Subdomain $subdomain, Closure $next): Response
    {
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
