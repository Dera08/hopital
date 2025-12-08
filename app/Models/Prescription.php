<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'medication',
        'dosage',
        'frequency',
        'start_date',
        'end_date',
        'instructions',
        'is_signed',
        'signed_at',
        'signature_hash',
        'allergy_checked',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
        'is_signed' => 'boolean',
        'allergy_checked' => 'boolean',
    ];

    // Relations
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSigned($query)
    {
        return $query->where('is_signed', true);
    }
}
