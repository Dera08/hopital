<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAct extends Model
{
    use HasFactory, BelongsToHospital;

    protected $fillable = [
        'patient_id',
        'prestation_id',
        'hospital_id',
        'status',          // pending, paid, completed, cancelled
        'price_at_time',   // Le prix facturé au moment de l'acte
        'priority',        // Récupéré du catalogue (Faible, Moyenne, Urgente)
    ];

    // Relation : L'acte concerne quel patient ?
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relation : Quelle est la prestation vendue ?
    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }
}