<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'appointment_datetime',
        'duration',
        'status',
        'type',
        'is_recurring',
        'recurrence_pattern',
        'reason',
        'notes',
        'reminder_sent',
        'reminder_sent_at',
    ];

    // Un rendez-vous appartient à un patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // Un rendez-vous est donné par un docteur (qui est un User)
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
 
