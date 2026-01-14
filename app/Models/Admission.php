<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\BelongsToHospital;

class Admission extends Model
{
    use HasFactory;
    use BelongsToHospital;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'hospital_id',
        'patient_id',
        'room_id',
        'doctor_id',
        'admission_date',
        'discharge_date',
        'admission_type',
        'status',
        'alert_level', // Fusionné
          // Fusionné
        'admission_reason',
        'discharge_summary',
        'bed_id',
        'appointment_id',
    ];

    /**
     * Le transtypage des attributs.
     */
    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
    ];

    // --- Relations ---

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

   public function derniersSignes()
{
    return $this->hasOneThrough(
        PatientVital::class, // Table finale
        Patient::class,      // Table intermédiaire
        'id',                // Clé sur Patient
        'patient_ipu',       // Clé sur PatientVital
        'patient_id',        // Clé sur Admission
        'ipu'                // Clé sur Patient
    )->withoutGlobalScopes()->where('patient_vitals.hospital_id', $this->hospital_id)->latest('created_at');
}

    // --- Scopes (Raccourcis de requêtes) ---

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCritical($query)
    {
        return $query->where('alert_level', 'critical');
    }

    // --- Méthodes Logiques ---

    /**
     * Met à jour le niveau d'alerte selon la température du patient
     */
    public function updateAlertLevel()
    {
        $latestTemp = $this->patient->clinicalObservations()
            ->where('type', 'temperature')
            ->latest('observation_datetime')
            ->first();

        if ($latestTemp && $latestTemp->value > 39) {
            $level = 'critical';
        } elseif ($latestTemp && $latestTemp->value > 38) {
            $level = 'warning';
        } else {
            $level = 'stable';
        }

        $this->update(['alert_level' => $level]);

        return $level;
    }
    public function bed()
{
    return $this->belongsTo(Bed::class);

}
public function appointment()
{
    return $this->belongsTo(Appointment::class);
}
}
