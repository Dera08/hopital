<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'target_type',
        'price',
        'duration_unit',
        'duration_value',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function subscriptions()
    {
        return $this->hasMany(HospitalSubscription::class, 'plan_id');
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'hospital_subscriptions')
                    ->withPivot('expires_at', 'is_active')
                    ->withTimestamps();
    }
}
