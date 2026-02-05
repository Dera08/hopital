<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;

echo "--- Debugging Nurse Assignment Logic ---\n";

// 1. Find the nurse user (assuming logged in user logic)
// Let's try to find 'Inf. Yao Marie' from the screenshot if possible, or any nurse
$nurse = User::where('name', 'like', '%Yao Marie%')->where('role', 'nurse')->first();
if (!$nurse) {
    $nurse = User::where('role', 'nurse')->first();
    echo "Nurse 'Yao Marie' not found, using: " . $nurse->name . "\n";
} else {
    echo "Found Nurse: " . $nurse->name . "\n";
}

if (!$nurse) {
    echo "No nurse found in DB.\n";
    exit;
}

echo "Nurse Service ID: " . $nurse->service_id . "\n";
$nurseService = Service::find($nurse->service_id);
echo "Nurse Service Name: " . ($nurseService ? $nurseService->name : 'N/A') . "\n";

// 2. Find the doctor 'TRAORÉ FATOUMATA'
$doctor = User::where('name', 'like', '%Traoré Fatoumata%')->orWhere('name', 'like', '%Traore Fatoumata%')->first();

if ($doctor) {
    echo "\nFound Dr. " . $doctor->name . "\n";
    echo "Doctor Service ID: " . $doctor->service_id . "\n";
    $docService = Service::find($doctor->service_id);
    echo "Doctor Service Name: " . ($docService ? $docService->name : 'N/A') . "\n";
    
    if ($doctor->service_id != $nurse->service_id) {
        echo "!!! MISMATCH: Doctor and Nurse are in different service IDs !!!\n";
    } else {
        echo "MATCH: Doctor and Nurse are in the same service.\n";
    }
} else {
    echo "\nDr. Traoré Fatoumata not found.\n";
}

// 3. Check recent appointments that are 'Non Assigné'
echo "\n--- Checking recent unassigned PatientVital records ---\n";
$unassigned = \App\Models\PatientVital::whereNull('doctor_id')
    ->where('hospital_id', $nurse->hospital_id)
    ->take(5)
    ->get();

foreach ($unassigned as $vital) {
    echo "Unassigned Vital ID: " . $vital->id . " - Patient: " . $vital->patient_name . "\n";
    // Check if there was an appointment for this patient
    $apt = Appointment::whereHas('patient', function($q) use ($vital) {
        $q->where('ipu', $vital->patient_ipu);
    })->latest()->first();
    
    if ($apt) {
        echo "  Found Appointment ID: " . $apt->id . " - Status: " . $apt->status . "\n";
        echo "  Appointment Doctor ID: " . ($apt->doctor_id ?? 'NULL') . "\n";
        if ($apt->doctor_id) {
            $aptDoc = User::find($apt->doctor_id);
            echo "  Appointment Doctor Service: " . ($aptDoc ? $aptDoc->service_id : 'N/A') . "\n";
            echo "  Matches Nurse? " . (($aptDoc && $aptDoc->service_id == $nurse->service_id) ? 'Yes' : 'NO') . "\n";
        }
    } else {
        echo "  No appointment found for this patient IPU.\n";
    }
}
