<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    use HasFactory;
    // On autorise le remplissage de ces colonnes
    protected $fillable = ['name', 'slug', 'address', 'logo', 'is_active'];

    // Un hôpital a plusieurs services
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    // Un hôpital a plusieurs utilisateurs
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}