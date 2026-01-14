<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prestation extends Model
{
    use HasFactory, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'service_id',
        'name',
        'code',
        'description',
        'price',
        'category',
        'is_active',
        'requires_payment',
        'payment_timing',
        'requires_approval',
        'approval_level',
        'is_emergency',
        'estimated_duration',
        'required_equipment',
        'priority',
        'destination_role',
        'is_pack'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'requires_payment' => 'boolean',
        'requires_approval' => 'boolean',
        'is_emergency' => 'boolean',
        'estimated_duration' => 'integer',
        'approval_level' => 'integer',
        'required_equipment' => 'array',
    ];

    // Une prestation appartient à un service
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Une prestation peut être liée à plusieurs rendez-vous
    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_prestations')
                    ->withPivot('quantity', 'unit_price', 'total', 'added_at', 'added_by')
                    ->withTimestamps();
    }

    // Scope pour prestations actives
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour prestations payantes
    public function scopePayable($query)
    {
        return $query->where('requires_payment', true);
    }

    // Scope par catégorie
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Récupérer le prix de consultation pour un service
    public static function getConsultationPrice($serviceId, $hospitalId)
    {
        return static::where('service_id', $serviceId)
                    ->where('hospital_id', $hospitalId)
                    ->where('category', 'consultation')
                    ->where('is_active', true)
                    ->first()?->price ?? 0;
    }

    // Scope pour prestations d'urgence
    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    // Scope pour prestations nécessitant une approbation
    public function scopeRequiresApproval($query)
    {
        return $query->where('requires_approval', true);
    }

    // Scope par priorité
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Scope pour prestations payées à l'avance
    public function scopePayBefore($query)
    {
        return $query->where('payment_timing', 'before');
    }

    // Vérifier si la prestation peut être effectuée (tous les critères remplis)
    public function canBePerformed()
    {
        // Logique métier pour vérifier les conditions
        return $this->is_active && (!$this->requires_approval || $this->approval_level <= auth()->user()->role_level ?? 0);
    }
}
