<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MedecinExterne extends Authenticatable
{
    use Notifiable;

    protected $table = 'medecins_externes';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'specialite',
        'numero_ordre',
        'adresse_cabinet',
        'password',
        'statut',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Accessor for name
    public function getNameAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // Relationship with wallet
    public function wallet()
    {
        return $this->hasOne(SpecialistWallet::class, 'specialist_id');
    }
}