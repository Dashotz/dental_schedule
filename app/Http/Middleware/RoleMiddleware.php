<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check admin guard first
        if (auth('admin')->check()) {
            // Admin is authenticated, allow access to admin routes
            if (in_array('admin', $roles)) {
                return $next($request);
            }
            abort(403, 'Unauthorized access');
        }

        // Check web guard (doctors)
        if (auth('web')->check()) {
            // Doctor is authenticated, allow access to doctor routes
            if (in_array('doctor', $roles)) {
                return $next($request);
            }
            abort(403, 'Unauthorized access');
        }

        // Not authenticated
        if (in_array('admin', $roles)) {
            return redirect()->route('admin.login');
        }
        return redirect()->route('doctor.login');
    }
}
