<?php

namespace App\Traits;

use App\Models\Subdomain;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

trait HasSubdomain
{
    /**
     * Get the subdomain that owns this model
     */
    public function subdomain(): BelongsTo
    {
        return $this->belongsTo(Subdomain::class);
    }

    /**
     * Scope a query to only include records for the current subdomain
     */
    public function scopeForSubdomain(Builder $query, ?Subdomain $subdomain = null): Builder
    {
        if (!$subdomain) {
            $subdomain = request()->attributes->get('current_subdomain');
        }

        if ($subdomain) {
            return $query->where('subdomain_id', $subdomain->id);
        }

        return $query;
    }

    /**
     * Boot the trait and add global scope
     */
    protected static function bootHasSubdomain(): void
    {
        // Add global scope to automatically filter by subdomain
        static::addGlobalScope('subdomain', function (Builder $query) {
            // Don't apply scope in admin panel or if explicitly disabled
            if (request()->is('admin/*') || request()->attributes->get('disable_subdomain_scope', false)) {
                return;
            }

            $subdomain = request()->attributes->get('current_subdomain');
            
            if ($subdomain) {
                $query->where('subdomain_id', $subdomain->id);
            }
        });
    }
}

