<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Service extends Model

{
     use HasFactory, BelongsToHospital;
    protected $fillable = ['name', 'code','hospital_id','description', 'consultation_price'];

    // Un service a plusieurs utilisateurs (Docteurs, Infirmiers)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    // Un service a plusieurs chambres/lits (table 'rooms')
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    // Un service a plusieurs prestations
    public function prestations(): HasMany
    {
        return $this->hasMany(Prestation::class);
    }

    // Récupérer le prix de consultation pour ce service
    public function getConsultationPriceAttribute(): float
    {
        return $this->prestations()
                   ->where('category', 'consultation')
                   ->where('is_active', true)
                   ->first()?->price ?? 0;
    }
}
 