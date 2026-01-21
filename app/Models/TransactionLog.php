<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hospital;
use App\Models\MedecinExterne;

class TransactionLog extends Model
{
    protected $fillable = [
        'source_type',
        'source_id',
        'amount',
        'fee_applied',
        'net_income',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_applied' => 'decimal:2',
        'net_income' => 'decimal:2',
    ];

    // Relationships
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'source_id')->where('source_type', 'hospital');
    }

    public function specialist()
    {
        return $this->belongsTo(\App\Models\MedecinExterne::class, 'source_id')->where('source_type', 'specialist');
    }

    // Scopes
    public function scopeHospitalTransactions($query)
    {
        return $query->where('source_type', 'hospital');
    }

    public function scopeSpecialistTransactions($query)
    {
        return $query->where('source_type', 'specialist');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }
}
