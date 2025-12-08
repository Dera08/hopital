<?php

namespace App\Models;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};
class ClinicalObservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'user_id', 'type', 'value', 'unit',
        'observation_datetime', 'notes', 'is_critical'
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

    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('observation_datetime', '>=', now()->subDays($days));
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'blood_pressure' => 'Tension Artérielle',
            'temperature' => 'Température',
            'heart_rate' => 'Fréquence Cardiaque',
            'weight' => 'Poids',
            'height' => 'Taille',
            'oxygen_saturation' => 'Saturation O2',
            'glucose' => 'Glycémie',
        ];

        return $labels[$this->type] ?? $this->type;
    }
}