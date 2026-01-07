<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RegistrationLink extends Model
{
    protected $fillable = [
        'subdomain_id',
        'created_by',
        'token',
        'link',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function subdomain(): BelongsTo
    {
        return $this->belongsTo(Subdomain::class);
    }

    public function createdBy()
    {
        // Check if created_by exists in admins table first, then users
        // This is a workaround since created_by can reference either admins or users
        $admin = \App\Models\Admin::find($this->created_by);
        if ($admin) {
            return $admin;
        }
        $user = \App\Models\User::find($this->created_by);
        return $user;
    }

    public function isUsable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // If max_uses is 0, it means unlimited uses
        if ($this->max_uses > 0 && $this->used_count >= $this->max_uses) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if token is cryptographically secure (minimum length check)
     */
    public static function isValidTokenFormat(string $token): bool
    {
        // Token should be at least 32 characters for security
        return strlen($token) >= 32 && ctype_alnum($token);
    }

    public static function generateToken(): string
    {
        return Str::random(32);
    }
}
