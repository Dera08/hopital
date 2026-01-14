<?php

namespace App\Models;
use App\Traits\BelongsToHospital;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};

// ============ ROOM MODEL ============
class Room extends Model
{
    use HasFactory;
    use BelongsToHospital;

    protected $fillable = [
        'hospital_id','room_number', 'bed_capacity', 'service_id', 
        'status', 'type', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'bed_capacity' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function currentAdmission()
    {
        return $this->hasOne(Admission::class)->where('status', 'active')->latest();
    }

    public function patientVital()
    {
        return $this->belongsTo(PatientVital::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }
    // Dans App\Models\Room.php

public function beds()
{
    return $this->hasMany(Bed::class);
}
} 