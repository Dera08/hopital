<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};
class DoctorLeave extends Model
{
    use HasFactory;
     use BelongsToHospital;

    protected $fillable = [
       'hospital_id', 'doctor_id', 'start_date', 'end_date', 
        'leave_type', 'reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }
}