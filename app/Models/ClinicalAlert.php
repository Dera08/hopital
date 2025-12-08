<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};
class ClinicalAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'triggered_by_id', 'alert_type', 'severity',
        'message', 'is_acknowledged', 'acknowledged_by_id', 'acknowledged_at'
    ];

    protected $casts = [
        'is_acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by_id');
    }

    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by_id');
    }

    public function scopeUnacknowledged($query)
    {
        return $query->where('is_acknowledged', false);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'blue',
            default => 'gray',
        };
    }
}
