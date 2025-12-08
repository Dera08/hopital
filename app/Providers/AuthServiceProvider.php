<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{Patient, Prescription, MedicalRecord, Admission};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        Patient::class => \App\Policies\PatientPolicy::class,
        Prescription::class => \App\Policies\PrescriptionPolicy::class,
        MedicalRecord::class => \App\Policies\MedicalRecordPolicy::class,
        Admission::class => \App\Policies\AdmissionPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

 