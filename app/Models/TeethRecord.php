<?php

namespace App\Models;

use App\Traits\HasSubdomain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeethRecord extends Model
{
    use HasSubdomain;

    protected $fillable = [
        'subdomain_id',
        'patient_id',
        'tooth_number',
        'condition',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'tooth_number' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
