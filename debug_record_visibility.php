<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\PatientVital;

// 1. Find the Cardiologist
$cardiologist = User::where('service_id', 1)->where('role', 'doctor')->first(); // Assuming Service 1 is Cardio based on previous logs
if (!$cardiologist) {
    // Try to find any doctor
    $cardiologist = User::where('role', 'doctor')->first();
}

// 2. Find Record #13
$record = PatientVital::find(13);

echo "--- Check Visibility ---\n";

if ($cardiologist) {
    echo "Cardiologist: " . $cardiologist->name . " (ID: " . $cardiologist->id . ")\n";
    echo "Role: " . $cardiologist->role . "\n";
    echo "Service ID: " . $cardiologist->service_id . "\n";
} else {
    echo "No Cardiologist found.\n";
}

if ($record) {
    echo "\nRecord #13:\n";
    echo "Status: " . $record->status . "\n";
    echo "Doctor ID: " . ($record->doctor_id ?? 'NULL') . "\n";
    echo "Service ID: " . $record->service_id . "\n";
    echo "Hospital ID: " . $record->hospital_id . "\n";
    
    // Simulate the query failure
    if ($cardiologist) {
        $shouldBeVisible = true;
        
        // Status check
        if (!($record->status == 'active' || $record->status === null)) {
             echo "[FAIL] Status not active/null (is '{$record->status}').\n";
             $shouldBeVisible = false;
        }

        // Admitted check
        if ($record->status == 'admitted') {
             echo "[FAIL] Status is admitted.\n";
             $shouldBeVisible = false;
        }
        
        // Hospital check
        if ($record->hospital_id != $cardiologist->hospital_id) {
             echo "[FAIL] Hospital mismatch.\n";
             $shouldBeVisible = false;
        }

        // Doctor Assignment check (The suspect)
        if ($cardiologist->role === 'doctor' || $cardiologist->role === 'internal_doctor') {
            if ($record->doctor_id != $cardiologist->id) {
                echo "[FAIL] Doctor ID mismatch (Record: " . ($record->doctor_id ?? 'NULL') . " != User: " . $cardiologist->id . ")\n";
                $shouldBeVisible = false;
            }
        }
        
        echo $shouldBeVisible ? "\nResult: VISIBLE" : "\nResult: NOT VISIBLE";
    }
} else {
    echo "Record #13 not found.\n";
}
