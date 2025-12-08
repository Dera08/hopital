<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'patient_id', 'admission_id', 
        'invoice_date', 'due_date', 'subtotal', 'tax', 
        'total', 'status', 'paid_at', 'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' 
            && $this->due_date 
            && $this->due_date->isPast();
    }
}