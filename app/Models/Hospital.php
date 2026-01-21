<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    use HasFactory;
    // On autorise le remplissage de ces colonnes
    protected $fillable = ['name', 'slug', 'address', 'logo', 'is_active', 'subscription_plan_id'];

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

    // Un hôpital a plusieurs patients
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    // Un hôpital a plusieurs prestations
    public function prestations(): HasMany
    {
        return $this->hasMany(Prestation::class);
    }

    // Un hôpital appartient à un plan d'abonnement
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}