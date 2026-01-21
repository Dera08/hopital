<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionRate extends Model
{
    protected $fillable = [
        'service_type',
        'activation_fee',
        'commission_percentage',
        'is_active',
    ];

    protected $casts = [
        'activation_fee' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function brackets()
    {
        return $this->hasMany(CommissionBracket::class)->orderBy('order');
    }
}
