<?php

namespace App\Models;

use App\Traits\HasSubdomain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasSubdomain;

    protected $fillable = [
        'subdomain_id',
        'patient_id',
        'doctor_id',
        'created_by',
        'appointment_date',
        'duration',
        'type',
        'status',
        'notes',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'datetime',
            'duration' => 'integer',
        ];
    }

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
