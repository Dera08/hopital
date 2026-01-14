<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PrestationPack extends Model
{
    use HasFactory, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'name',
        'description',
        'total_price',
        'discounted_price',
        'is_active'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Une pack peut contenir plusieurs prestations
    public function prestations(): BelongsToMany
    {
        return $this->belongsToMany(Prestation::class, 'pack_prestations')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Scope pour packs actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Calculer le prix total des prestations dans la pack
    public function calculateTotalPrice()
    {
        return $this->prestations->sum(function ($prestation) {
            return $prestation->price * $prestation->pivot->quantity;
        });
    }

    // Calculer le prix avec remise
    public function getEffectivePrice()
    {
        return $this->discounted_price ?? $this->total_price;
    }
}
