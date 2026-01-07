<?php

namespace App\Models;

use App\Traits\HasSubdomain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    use HasSubdomain;

    protected $fillable = [
        'subdomain_id',
        'patient_id',
        'appointment_id',
        'treatment_plan_id',
        'procedure_id',
        'doctor_id',
        'treatment_date',
        'diagnosis',
        'treatment_notes',
        'prescription',
        'status',
        'cost',
    ];

    protected function casts(): array
    {
        return [
            'treatment_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function treatmentPlan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
