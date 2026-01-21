<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialistWallet extends Model
{
    protected $fillable = [
        'specialist_id',
        'balance',
        'is_activated',
        'is_blocked',
        'activated_at',
        'last_recharge_at',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_activated' => 'boolean',
        'is_blocked' => 'boolean',
        'activated_at' => 'datetime',
        'last_recharge_at' => 'datetime',
    ];

    // Relationships
    public function specialist()
    {
        return $this->belongsTo(MedecinExterne::class, 'specialist_id');
    }

    // Scopes
    public function scopeActivated($query)
    {
        return $query->where('is_activated', true);
    }

    public function scopeNotBlocked($query)
    {
        return $query->where('is_blocked', false);
    }

    public function scopeHasBalance($query, $amount = 0)
    {
        return $query->where('balance', '>=', $amount);
    }

    // Helper methods
    public function canDeduct($amount)
    {
        return $this->is_activated && !$this->is_blocked && $this->balance >= $amount;
    }

    public function addBalance($amount)
    {
        $this->balance += $amount;
        $this->last_recharge_at = now();
        $this->save();

        return $this;
    }

    public function deductBalance($amount)
    {
        if (!$this->canDeduct($amount)) {
            throw new \Exception('Cannot deduct from wallet: insufficient balance or wallet not active');
        }

        $this->balance -= $amount;
        $this->save();

        return $this;
    }

    public function activate()
    {
        $this->is_activated = true;
        $this->activated_at = now();
        $this->save();

        return $this;
    }

    public function block()
    {
        $this->is_blocked = true;
        $this->save();

        return $this;
    }

    public function unblock()
    {
        $this->is_blocked = false;
        $this->save();

        return $this;
    }
}
