<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\PatientVital;
use App\Models\Appointment;

echo "--- AUTO-ASSIGNING UNASSIGNED VITALS FROM TODAY ---\n";

// Get today's unassigned vitals
$unassignedVitals = PatientVital::whereDate('created_at', now()->toDateString())
    ->whereNull('doctor_id')
    ->get();

echo "Found " . $unassignedVitals->count() . " unassigned records.\n";

foreach ($unassignedVitals as $vital) {
    echo "\nProcessing: {$vital->patient_name} (ID: {$vital->id})\n";
    
    $hospitalId = $vital->hospital_id;
    // Use service_id directly from the record if available, otherwise try fallback
    $serviceId = $vital->service_id; 
    
    if (!$serviceId) {
        $creator = User::find($vital->user_id);
        if ($creator) {
            $serviceId = $creator->service_id;
        } else {
             echo "  - Service ID not found on record or creator, skipping.\n";
             continue;
        }
    }
    
    echo "  - Service ID: $serviceId\n";

    // 1. Try to find from Appointment
    $assignedDoctorId = null;
    $appointment = Appointment::whereHas('patient', function($q) use ($vital) {
            $q->where('ipu', $vital->patient_ipu);
        })
        ->where('hospital_id', $hospitalId)
        ->where('service_id', $serviceId)
        ->whereIn('status', ['paid', 'scheduled', 'confirmed', 'prepared'])
        ->orderByRaw("FIELD(status, 'paid') DESC")
        ->orderBy('appointment_datetime', 'desc')
        ->first();
        
    if ($appointment && $appointment->doctor_id) {
        // Strict service check
        $doc = User::find($appointment->doctor_id);
        if ($doc && $doc->service_id === $serviceId) {
            $assignedDoctorId = $appointment->doctor_id;
            echo "  - Found valid appointment doctor: {$doc->name}\n";
        }
    }
    
    // 2. Round Robin Logic (Copy of Controller Logic)
    if (!$assignedDoctorId) {
        $dayName = strtolower(\Carbon\Carbon::now()->locale('en')->isoFormat('dddd'));
        
        // Pass 1: Strict
        $availableDoctors = User::where('hospital_id', $hospitalId)
            ->where('service_id', $serviceId)
            ->whereIn('role', ['doctor', 'internal_doctor'])
            ->where('is_active', true)
            ->whereHas('availabilities', function($query) use ($dayName) {
                $query->where('day_of_week', $dayName)
                      ->where('is_active', true);
            })
            ->get();
            
        // Pass 2: Fallback
        if ($availableDoctors->isEmpty()) {
            echo "  - No strict availability, using fallback (all active docs in service).\n";
            $availableDoctors = User::where('hospital_id', $hospitalId)
                ->where('service_id', $serviceId)
                ->whereIn('role', ['doctor', 'internal_doctor'])
                ->where('is_active', true)
                ->get();
        }
        
        $minCount = 9999;
        $bestDoctorId = null;
        
        foreach ($availableDoctors as $doctor) {
            $count = PatientVital::where('doctor_id', $doctor->id)
                ->whereDate('created_at', now()->toDateString())
                ->where('status', '!=', 'archived')
                ->count();
                
            if ($count < $minCount) {
                $minCount = $count;
                $bestDoctorId = $doctor->id;
            }
            
            if ($count < 3) {
                $assignedDoctorId = $doctor->id;
                echo "  - Assigned via Round Robin (<3): {$doctor->name}\n";
                break;
            }
        }
        
        if (!$assignedDoctorId && $bestDoctorId) {
            $assignedDoctorId = $bestDoctorId;
             echo "  - Assigned via Best Available (Fallback): " . User::find($bestDoctorId)->name . "\n";
        }
    }
    
    if ($assignedDoctorId) {
        $vital->update(['doctor_id' => $assignedDoctorId]);
        echo "  -> SUCCESS: Updated record with Doctor ID $assignedDoctorId\n";
    } else {
        echo "  -> FAILED: Could not find any doctor to assign.\n";
    }
}
