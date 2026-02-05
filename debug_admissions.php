<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\Admission;
use App\Models\Patient;

echo "--- Debugging Admissions for Dr. Kouassi ---\n";

// 1. Find Doctor
$doctor = User::with('service')->where('name', 'like', '%Kouassi%')->first();
if (!$doctor) {
    die("❌ Doctor 'Kouassi' not found.\n");
}

echo "Doctor: " . $doctor->name . " (ID: " . $doctor->id . ")\n";
echo "Service: " . ($doctor->service->name ?? 'None') . " (ID: " . ($doctor->service_id ?? 'NULL') . ")\n";
echo "Hospital ID: " . $doctor->hospital_id . "\n\n";

// 2. Fetch All Active Admissions for this Hospital
$admissions = Admission::withoutGlobalScopes()
    ->with(['patient', 'room.service', 'doctor'])
    ->where('hospital_id', $doctor->hospital_id)
    ->where('status', 'active')
    ->get();

echo "Found " . $admissions->count() . " active admissions in Hospital {$doctor->hospital_id}.\n";

foreach ($admissions as $adm) {
    echo "\n------------------------------------------------\n";
    echo "Admission ID: " . $adm->id . "\n";
    echo "Patient: " . ($adm->patient->name ?? 'Unknown') . "\n";
    echo "Status: " . $adm->status . "\n";
    echo "Assigned Doctor ID: " . ($adm->doctor_id ?? 'NULL') . "\n";
    
    // Check Room Info
    if ($adm->room) {
        echo "Room: " . $adm->room->room_number . "\n";
        echo "Room Service ID: " . ($adm->room->service_id ?? 'NULL') . "\n";
        echo "Room Service Name: " . ($adm->room->service->name ?? 'Unknown') . "\n";
    } else {
        echo "❌ Room: NONE assigned to admission.\n";
    }

    // Evaluate Visibility Logic (Simulate Dashboard Logic)
    $byDoctor = ($adm->doctor_id == $doctor->id);
    
    $byService = false;
    if ($doctor->service_id && $adm->room && $adm->room->service_id == $doctor->service_id) {
        $byService = true;
    }

    echo "Visible by Doctor ID? " . ($byDoctor ? "YES" : "NO") . "\n";
    echo "Visible by Service ID? " . ($byService ? "YES" : "NO") . "\n";

    if (!$byDoctor && !$byService) {
        echo "🔴 RESULT: HIDDEN from Dashboard.\n";
        echo "   -> Reason: Doctor ID mismatch AND (Room Service ID mismatch OR Room missing).\n";
    } else {
        echo "🟢 RESULT: VISIBLE on Dashboard.\n";
    }
}
