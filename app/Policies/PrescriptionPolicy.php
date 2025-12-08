<?php

namespace App\Policies;

use App\Models\{User, Prescription};

class PrescriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isDoctor() || $user->isNurse() || $user->isAdmin();
    }

    public function view(User $user, Prescription $prescription): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor() && $prescription->doctor_id === $user->id) {
            return true;
        }

        if ($user->isNurse() && $user->service_id) {
            return $prescription->patient->admissions()
                ->where('status', 'active')
                ->whereHas('room', function($q) use ($user) {
                    $q->where('service_id', $user->service_id);
                })
                ->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isDoctor();
    }

    public function update(User $user, Prescription $prescription): bool
    {
        return $user->isDoctor() 
            && $prescription->doctor_id === $user->id 
            && !$prescription->is_signed;
    }

    public function delete(User $user, Prescription $prescription): bool
    {
        return $user->isAdmin() 
            || ($user->isDoctor() && $prescription->doctor_id === $user->id && !$prescription->is_signed);
    }

    public function sign(User $user, Prescription $prescription): bool
    {
        return $user->isDoctor() && $prescription->doctor_id === $user->id;
    }
}

// ============ config/hospisis.php (Configuration personnalisée) ============

return [

    /*
    |--------------------------------------------------------------------------
    | HospitSIS Configuration
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'HospitSIS'),
    'version' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Compliance & Security
    |--------------------------------------------------------------------------
    */

    'hds_compliance' => env('HDS_COMPLIANCE', true),
    'mfa_required' => env('MFA_REQUIRED', false),
    'audit_retention_days' => env('AUDIT_RETENTION_DAYS', 1095), // 3 ans

    /*
    |--------------------------------------------------------------------------
    | Business Rules
    |--------------------------------------------------------------------------
    */

    'appointment' => [
        'default_duration' => 30, // minutes
        'min_advance_booking_hours' => 2,
        'max_advance_booking_days' => 90,
        'cancellation_deadline_hours' => 24,
    ],

    'admission' => [
        'auto_discharge_days' => null, // null = manual only
    ],

    'prescription' => [
        'require_signature' => true,
        'require_allergy_check' => true,
    ],

    'document' => [
        'max_file_size_mb' => 10,
        'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
        'require_medical_validation' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    'notifications' => [
        'appointment_reminder_hours' => 24,
        'send_sms' => env('SEND_SMS_NOTIFICATIONS', false),
        'send_email' => env('SEND_EMAIL_NOTIFICATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | KPI Targets
    |--------------------------------------------------------------------------
    */

    'kpi_targets' => [
        'appointment_call_reduction' => 50, // %
        'prescription_error_reduction' => 20, // %
        'user_adoption_rate' => 95, // %
        'admission_time_reduction' => 40, // %
        'data_inconsistency_rate' => 1, // %
    ],

]; 
