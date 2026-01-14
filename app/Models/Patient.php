<?php

namespace App\Models;

use App\Models\PatientVital; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Admission;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\ClinicalObservation;
use App\Models\MedicalDocument;
use App\Traits\BelongsToHospital;

class Patient extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, BelongsToHospital;

    protected $fillable = [
        'hospital_id','ipu', 'name', 'first_name', 'dob', 'gender', 
        'address', 'city', 'postal_code', 'phone', 'email',
        'emergency_contact_name', 'emergency_contact_phone',
        'blood_group', 'allergies', 'medical_history', 'is_active',
        'password',
        'referring_doctor_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
        'allergies' => 'array',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // ✅ CORRECTION CRITIQUE DU GLOBAL SCOPE
    protected static function booted()
    {
        static::addGlobalScope('hospital', function ($builder) {
            // On n'applique le scope hospital QUE pour le staff (guard 'web')
            // Pas pour les patients authentifiés via le portail
            if (auth()->guard('web')->check()) {
                $user = auth()->guard('web')->user();
                if ($user && isset($user->hospital_id)) {
                    $builder->where('hospital_id', $user->hospital_id);
                }
            }
        });
    }

    // --- RELATIONS ---
    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function clinicalObservations()
    {
        return $this->hasMany(ClinicalObservation::class);
    }

    public function documents()
    {
        return $this->hasMany(MedicalDocument::class);
    }
    
    public function referringDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referring_doctor_id');
    }

    public function vitals()
    {
        return $this->hasMany(PatientVital::class, 'patient_ipu', 'ipu');
    }

    // --- ACCESSEURS ---
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->name;
    }

    public function getAgeAttribute(): int
    {
        return $this->dob ? $this->dob->age : 0;
    }

    // --- MÉTHODES STATIQUES ---
    public static function generateIpu(): string
    {
        do {
            $ipu = 'PAT' . date('Y') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::withoutGlobalScope('hospital')->where('ipu', $ipu)->exists());
        
        return $ipu;
    }

    // --- MÉTHODES D'AUTHENTIFICATION ---
    public function getAuthIdentifierName()
    {
        return 'id'; // ✅ Utilise l'ID, pas l'email
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}