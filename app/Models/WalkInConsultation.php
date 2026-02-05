<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WalkInConsultation extends Model
{
    protected $fillable = [
        'hospital_id',
        'patient_id',
        'service_id',
        'status',
        'consultation_datetime',
        'payment_transaction_id',
        'payment_method',
        'payment_operator',
        'cashier_id',
    ];

    protected $casts = [
        'consultation_datetime' => 'datetime',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function prestations(): BelongsToMany
    {
        return $this->belongsToMany(Prestation::class, 'walk_in_consultation_prestations')
            ->withPivot('quantity', 'unit_price', 'total')
            ->withTimestamps();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
