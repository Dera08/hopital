<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabInventory extends Model
{

    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'hospital_id',
        'name',
        'unit',
        'quantity',
        'min_threshold',
        'batch_number',
        'expiry_date',
        'description',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
