<?php

namespace App\Services\Tenant;

use App\Models\Subdomain;
use Illuminate\Http\Request;

class TenantContext
{
    /**
     * Get the current subdomain from request attributes
     */
    public static function getCurrentSubdomain(): ?Subdomain
    {
        return request()->attributes->get('current_subdomain');
    }

    /**
     * Set the current subdomain in request attributes
     */
    public static function setCurrentSubdomain(Subdomain $subdomain): void
    {
        request()->attributes->set('current_subdomain', $subdomain);
    }

    /**
     * Get the current subdomain ID
     */
    public static function getSubdomainId(): ?int
    {
        $subdomain = self::getCurrentSubdomain();
        return $subdomain?->id;
    }

    /**
     * Ensure a subdomain context exists, abort if not
     */
    public static function ensureSubdomain(): Subdomain
    {
        $subdomain = self::getCurrentSubdomain();
        
        if (!$subdomain) {
            abort(403, 'Subdomain context required. This action requires a valid subdomain.');
        }
        
        return $subdomain;
    }

    /**
     * Check if current request is in admin context
     */
    public static function isAdminContext(): bool
    {
        return request()->is('admin/*') && auth('admin')->check();
    }

    /**
     * Check if subdomain scope should be disabled
     */
    public static function shouldDisableScope(): bool
    {
        return self::isAdminContext() || 
               request()->attributes->get('disable_subdomain_scope', false);
    }

    /**
     * Verify that a model belongs to the current subdomain
     */
    public static function verifyOwnership($model, string $modelName = 'Resource'): void
    {
        $subdomain = self::ensureSubdomain();
        
        if (!isset($model->subdomain_id)) {
            abort(500, "Model {$modelName} does not have subdomain_id attribute");
        }
        
        if ($model->subdomain_id !== $subdomain->id) {
            \Log::warning('Unauthorized access attempt', [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'model_subdomain_id' => $model->subdomain_id,
                'current_subdomain_id' => $subdomain->id,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
            
            abort(403, "Unauthorized access to {$modelName}. This resource belongs to a different subdomain.");
        }
    }

    /**
     * Get tenant-specific cache key
     */
    public static function getCacheKey(string $key, ?int $subdomainId = null): string
    {
        $subdomainId = $subdomainId ?? self::getSubdomainId();
        
        if (!$subdomainId) {
            return $key;
        }
        
        return "subdomain_{$subdomainId}_{$key}";
    }

    /**
     * Get cache tags for tenant-specific caching
     */
    public static function getCacheTags(?int $subdomainId = null): array
    {
        $subdomainId = $subdomainId ?? self::getSubdomainId();
        
        if (!$subdomainId) {
            return [];
        }
        
        return ['subdomain_' . $subdomainId];
    }
}

