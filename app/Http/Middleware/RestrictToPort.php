<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictToPort
{
    /**
     * Handle an incoming request.
     * Restricts routes to a specific port
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $port): Response
    {
        $currentPort = $request->getPort();
        
        if ($currentPort != $port) {
            abort(403, 'This page is only accessible on port ' . $port . '. Please use http://127.0.0.1:' . $port . $request->getPathInfo());
        }
        
        return $next($request);
    }
}

