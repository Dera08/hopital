<?php

namespace App\Models;


use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};
class DoctorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'day_of_week', 'start_time', 
        'end_time', 'slot_duration', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'slot_duration' => 'integer',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
