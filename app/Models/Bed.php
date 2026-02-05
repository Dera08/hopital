<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use BelongsToHospital;

    protected $fillable = ['bed_number', 'room_id', 'is_available', 'hospital_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            Admission::class,
            'bed_id',        // clé étrangère sur Admissions
            'id',            // clé primaire sur Patients
            'id',            // clé primaire sur Beds
            'patient_id'     // clé étrangère sur Admissions
        )->where('admissions.status', 'active');
    }

    public function getBedTagAttribute()
    {
        return $this->bed_number;
    }

    public function getStatusAttribute()
    {
        return $this->is_available ? 'available' : 'occupied';
    }
}