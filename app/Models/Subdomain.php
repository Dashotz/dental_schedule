<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subdomain extends Model
{
    protected $fillable = [
        'subdomain',
        'name',
        'description',
        'email',
        'phone',
        'address',
        'is_active',
        'subscription_expires_at',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'subscription_expires_at' => 'date',
            'settings' => 'array',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function registrationLinks(): HasMany
    {
        return $this->hasMany(RegistrationLink::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest('end_date')
            ->first();
    }

    public function isSubscriptionActive(): bool
    {
        // Cache the result for 5 minutes to avoid repeated queries
        return cache()->remember("subdomain_{$this->id}_subscription_active", 300, function() {
            return $this->subscriptions()
                ->where('status', 'active')
                ->where('end_date', '>=', now())
                ->exists();
        });
    }
}
