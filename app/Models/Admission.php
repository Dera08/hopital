<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'patient_id',
        'room_id',
        'doctor_id',
        'admission_date',
        'discharge_date',
        'admission_type',
        'status',
        'admission_reason',
        'discharge_summary',
    ];

    // Un séjour appartient à un patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // Un séjour est effectué dans une chambre/lit
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // Un séjour est géré par un médecin
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
 