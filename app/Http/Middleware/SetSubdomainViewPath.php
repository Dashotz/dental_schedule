<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subdomain;
use App\Services\SubdomainTemplateService;
use Symfony\Component\HttpFoundation\Response;

class SetSubdomainViewPath
{
    /**
     * Handle an incoming request and set the view path for subdomain-specific views
     */
    public function handle(Request $request, Closure $next): Response
    {
        $port = $request->getPort();
        $subdomain = null;
        
        // Get subdomain from request attributes (set by CheckSubdomainStatus middleware)
        $subdomain = $request->attributes->get('current_subdomain');
        
        // If not set, try to get from port
        if (!$subdomain && $port >= 10000) {
            $subdomain = Subdomain::where('port', $port)->first();
        }
        
        // If we have a subdomain, set the view path in request
        if ($subdomain) {
            $viewPath = SubdomainTemplateService::getViewPath($subdomain);
            $request->attributes->set('subdomain_view_path', $viewPath);
        } else {
            // Default to subdomain-template
            $request->attributes->set('subdomain_view_path', 'subdomain-template');
        }
        
        return $next($request);
    }
}

