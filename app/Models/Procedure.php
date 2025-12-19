<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'duration',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function treatmentPlanItems(): HasMany
    {
        return $this->hasMany(TreatmentPlanItem::class);
    }

    // Get current active price
    public function getCurrentPrice()
    {
        return $this->prices()
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('effective_from')
                    ->orWhere('effective_from', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', now());
            })
            ->first();
    }
}
