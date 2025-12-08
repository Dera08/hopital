<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'ipu', 'name', 'first_name', 'dob', 'gender', 
        'address', 'city', 'postal_code', 'phone', 'email',
        'emergency_contact_name', 'emergency_contact_phone',
        'blood_group', 'allergies', 'medical_history', 'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
        'allergies' => 'array',
        'is_active' => 'boolean',
        'password' => 'hashed', // Laravel 10+ automatique le hash
    ];

    // Relations
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

    // Accesseurs
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->name;
    }

    public function getAgeAttribute(): int
    {
        return $this->dob->age;
    }

    // Méthodes statiques
    public static function generateIpu(): string
    {
        do {
            $ipu = 'PAT' . date('Y') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('ipu', $ipu)->exists());
        
        return $ipu;
    }

    // Pour permettre la connexion avec IPU ou email
    public function getAuthIdentifierName()
    {
        return 'email'; // Par défaut email
    }

    // Retourner l'ID pour les sessions (pas l'email)
    public function getAuthIdentifier()
    {
        return $this->getKey(); // Retourne l'ID primaire
    }

    // Méthode pour trouver un patient par IPU ou email
    public static function findForAuth($identifier)
    {
        return static::where('email', $identifier)
            ->orWhere('ipu', $identifier)
            ->first();
    }
}