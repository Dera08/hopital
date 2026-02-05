<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

trait BelongsToHospital
{
    /**
     * Le "boot" du trait s'exécute automatiquement sur le modèle.
     */
    protected static $isApplyingHospitalScope = false;

    protected static function bootBelongsToHospital()
    {
        // 1. Filtrage automatique lors des requêtes (SELECT)
        static::addGlobalScope('hospital_filter', function (Builder $builder) {
            // Éviter les boucles infinies et les problèmes hors contexte web
            if (static::$isApplyingHospitalScope || app()->runningInConsole() || !app()->has('request')) {
                return;
            }

            static::$isApplyingHospitalScope = true;

            try {
                // Éviter le filtrage pour les Super Admin et les Patients
                // Les patients doivent pouvoir voir les services/prestations de tous les hôpitaux
                if (auth()->guard('superadmin')->check() || auth()->guard('patients')->check()) {
                    static::$isApplyingHospitalScope = false;
                    return;
                }

                $hospitalId = Session::get('hospital_id');
                
                // Si pas d'hospital_id en session, on check l'utilisateur connecté (Staff uniquement ici)
                if (!$hospitalId) {
                    if (auth()->guard('web')->check()) {
                        $hospitalId = auth()->guard('web')->user()->hospital_id;
                    }
                }

                if ($hospitalId) {
                    $table = $builder->getModel()->getTable();
                    $builder->where($table . '.hospital_id', $hospitalId);
                }
            } finally {
                static::$isApplyingHospitalScope = false;
            }
        });

        // 2. Assignation automatique lors de la création (INSERT)
        static::creating(function ($model) {
            if (app()->runningInConsole()) return;
            
            try {
                if (!auth()->guard('superadmin')->check() && !$model->hospital_id) {
                    $hospitalId = Session::get('hospital_id');
                    
                    if (!$hospitalId) {
                        if (auth()->guard('web')->check()) {
                            $hospitalId = auth()->guard('web')->user()->hospital_id;
                        } elseif (auth()->guard('patients')->check()) {
                            $hospitalId = auth()->guard('patients')->user()->hospital_id;
                        }
                    }
                    
                    if ($hospitalId) {
                        $model->hospital_id = $hospitalId;
                    }
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs d'auth lors de la création si pas possible
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