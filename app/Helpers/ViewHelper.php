<?php

namespace App\Helpers;

use App\Models\Subdomain;
use App\Services\SubdomainTemplateService;

class ViewHelper
{
    /**
     * Get the view path prefix for the current subdomain
     * Returns 'subdomain-template' if no subdomain-specific folder exists
     */
    public static function getSubdomainViewPath(?Subdomain $subdomain = null): string
    {
        if (!$subdomain) {
            // Try to get subdomain from request
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
     * Helper function to render subdomain-specific views
     * Usage: ViewHelper::view('index') instead of view('subdomain-template.index')
     */
    public static function view(string $view, array $data = []): \Illuminate\Contracts\View\View
    {
        $prefix = self::getSubdomainViewPath();
        return view($prefix . '.' . $view, $data);
    }
}

