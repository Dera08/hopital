<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

trait BelongsToHospital
{
    /**
     * Le "boot" du trait s'exécute automatiquement sur le modèle.
     */
    protected static function bootBelongsToHospital()
    {
        // 1. Filtrage automatique lors des requêtes (SELECT)
        // On vérifie si l'utilisateur est connecté et si un hospital_id est en session
        // Mais on exclut les superadmins qui doivent voir toutes les données
        if ((auth()->check() && !auth()->guard('superadmin')->check()) || Session::has('hospital_id')) {
            static::addGlobalScope('hospital_filter', function (Builder $builder) {
                $hospitalId = auth()->user()->hospital_id ?? Session::get('hospital_id');
                if ($hospitalId) {
                    $builder->where('hospital_id', $hospitalId);
                }
            });
        }

        // 2. Assignation automatique lors de la création (INSERT)
        static::creating(function ($model) {
            if (auth()->check() && !auth()->guard('superadmin')->check() && !$model->hospital_id) {
                $model->hospital_id = auth()->user()->hospital_id;
            }
        });
    }

    /**
     * Relation vers l'hôpital
     */
    public function hospital()
    {
        return $this->belongsTo(\App\Models\Hospital::class);
    }
}