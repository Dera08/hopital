<?php

namespace App\Models;
use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PatientVital extends Model
{
    use HasFactory, BelongsToHospital;

    /**
     * Les attributs qui peuvent être remplis massivement.
     */
    protected $fillable = [
        'patient_name',
        'patient_ipu',
        'temperature',
        'pulse',
        'blood_pressure',
        'weight',
        'urgency',
        'reason',
        'notes',
        'user_id',
        'hospital_id', // Ajouté par sécurité
        'service_id',  // Ajouté par sécurité
        'observations', // <--- Vérifiez que ceci est présent
        'ordonnance',
        'status',
        'is_visible_to_patient',
    ];

    /**
     * Relation : Un dossier de constantes appartient à une infirmière (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un dossier de constantes appartient à un patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_ipu', 'ipu');
    }

    /**
     * Relation : Un dossier de constantes est assigné à une chambre.
     */
    public function room(): HasOne
    {
        return $this->hasOne(Room::class, 'patient_vital_id');
    }
}