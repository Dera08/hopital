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
}