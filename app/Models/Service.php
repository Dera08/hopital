<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['name', 'description'];

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
}
 