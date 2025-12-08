<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};
class NursingNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'nurse_id', 'prescription_id', 
        'note_type', 'content', 'care_datetime', 'signature_hash'
    ];

    protected $casts = [
        'care_datetime' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function getNoteTypeLabelAttribute(): string
    {
        $labels = [
            'medication_administration' => 'Administration Médicament',
            'wound_care' => 'Soins de Plaie',
            'hygiene' => 'Soins d\'Hygiène',
            'observation' => 'Observation',
            'other' => 'Autre',
        ];

        return $labels[$this->note_type] ?? $this->note_type;
    }
}
