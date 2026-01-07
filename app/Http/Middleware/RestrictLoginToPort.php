<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictLoginToPort
{
    /**
     * Handle an incoming request.
     * Restricts login routes to port 9000 only
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $port = $request->getPort();
        $requiredPort = 9000;
        
        // Check if accessing login routes (admin or doctor)
        if ($request->is('admin/login') || $request->is('admin/logout') || 
            $request->is('doctor/login') || $request->is('doctor/logout')) {
            // Only allow on port 9000
            if ($port != $requiredPort) {
                $loginType = $request->is('admin/*') ? 'admin' : 'doctor';
                abort(403, ucfirst($loginType) . ' login is only accessible on port ' . $requiredPort . '. Please use http://127.0.0.1:' . $requiredPort . '/' . $loginType . '/login');
            }
        }
        
        return $next($request);
    }
}

