<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureParentDomain
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
        
        // Only allow access from parent domain
        if ($host !== $parentDomain) {
            abort(403, 'Admin panel is only accessible from the parent domain: ' . $parentDomain);
        }
        
        return $next($request);
    }
}
