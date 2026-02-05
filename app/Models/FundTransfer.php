<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FundTransfer extends Model
{
    protected $fillable = [
        'cashier_id',
        'admin_id',
        'amount',
        'received_amount',
        'gap_amount',
        'type',
        'status',
        'notes',
        'transfer_date',
        'validated_at'
    ];

    protected $casts = [
        'transfer_date' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
