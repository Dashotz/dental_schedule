<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowSubdomainPorts
{
    /**
     * Handle an incoming request.
     * Allows access on port 8000 (generic) OR ports 10000+ (subdomain-specific)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $port = $request->getPort();
        
        // Allow port 8000 (generic subdomain-template) or ports 10000+ (subdomain-specific)
        if ($port == 8000 || $port >= 10000) {
            return $next($request);
        }
        
        abort(403, 'This page is only accessible on port 8000 or subdomain-specific ports (10000+).');
    }
}

