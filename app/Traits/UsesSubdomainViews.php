<?php

namespace App\Traits;

use App\Services\SubdomainTemplateService;
use App\Models\Subdomain;

trait UsesSubdomainViews
{
    /**
     * Get the view path prefix for the current subdomain
     */
    protected function getSubdomainViewPath(?Subdomain $subdomain = null): string
    {
        if (!$subdomain) {
            // Try to get from request attributes
            $subdomain = request()->attributes->get('current_subdomain');
        }
        
        if (!$subdomain) {
            // Try to get from port
            $port = request()->getPort();
            if ($port >= 10000) {
                $subdomain = Subdomain::where('port', $port)->first();
            }
        }
        
        if ($subdomain) {
            return SubdomainTemplateService::getViewPath($subdomain);
        }
        
        // Default fallback
        return 'subdomain-template';
    }
    
    /**
     * Render a subdomain-specific view
     */
    protected function subdomainView(string $view, array $data = [])
    {
        $prefix = $this->getSubdomainViewPath();
        return view($prefix . '.' . $view, $data);
    }
}

