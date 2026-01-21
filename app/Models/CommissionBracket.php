<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionBracket extends Model
{
    protected $fillable = [
        'commission_rate_id',
        'min_price',
        'max_price',
        'percentage',
        'order',
    ];

    protected $casts = [
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    public function commissionRate()
    {
        return $this->belongsTo(CommissionRate::class);
    }
}
