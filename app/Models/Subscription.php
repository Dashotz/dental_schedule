<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'subdomain_id',
        'plan_name',
        'amount',
        'billing_cycle',
        'start_date',
        'end_date',
        'status',
        'last_payment_date',
        'next_payment_date',
        'payment_details',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'last_payment_date' => 'date',
            'next_payment_date' => 'date',
            'payment_details' => 'array',
        ];
    }

    public function subdomain(): BelongsTo
    {
        return $this->belongsTo(Subdomain::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now();
    }
}
