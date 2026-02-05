<?php

namespace App\Models;
use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

class ClinicalObservation extends Model
{
    use HasFactory;
    use BelongsToHospital;

    protected $fillable = [
        'hospital_id','patient_id', 'user_id', 'type', 'temperature', 'pulse', 
        'weight', 'height', 'observation_datetime', 'value','is_critical', 'is_visible_to_patient', 'notes'
    ];

    protected $casts = [
        'observation_datetime' => 'datetime',
        'is_critical' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ... reste du code (scopes et attributes) sans changement
}