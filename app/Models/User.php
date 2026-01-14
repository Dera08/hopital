<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Service;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;
     use BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'name',
        'email',
        'password',
        'role',
        'service_id',
        'is_active',
        'phone',
        'registration_number',
        'mfa_enabled',
        'mfa_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'mfa_enabled' => 'boolean',
        ];
    }
    
    public function service(): BelongsTo 
    {
        return $this->belongsTo(Service::class); 
    }
    
    // --- NOUVELLES MÉTHODES DE RÔLE DOCTEUR ---
    
    // Vérifie si l'utilisateur est un médecin INTERNE
    public function isInternalDoctor(): bool
    {
        return $this->role === 'internal_doctor';
    }

    // Vérifie si l'utilisateur est un médecin EXTERNE
    public function isExternalDoctor(): bool
    {
        return $this->role === 'external_doctor';
    }

    // Mise à jour : Vérifie si l'utilisateur est un DOCTEUR (Interne ou Externe)
    public function isDoctor(): bool
    {
        // Inclure le rôle 'doctor' générique pour la compatibilité
        return $this->role === 'doctor' || $this->isInternalDoctor() || $this->isExternalDoctor();
    }
    
    // --- AUTRES MÉTHODES (inchangées) ---

    public function isAdministrative(): bool
    {
        return $this->role === 'administrative' || $this->role === 'admin';
    }
    
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isNurse(): bool
    {
        return $this->role === 'nurse';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function hasRole($role): bool
 {
    return $this->role === $role;
 }
}