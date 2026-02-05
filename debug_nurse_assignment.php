<?php

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;

$nurseEmail = 'infirmier@exemple.com'; // Replace with an actual nurse email or ID if known, or just pick the first nurse
$nurse = User::where('role', 'nurse')->first();

if (!$nurse) {
    echo "No nurse found.\n";
    exit;
}

echo "Nurse: " . $nurse->name . " (Service ID: " . $nurse->service_id . ")\n";
$service = Service::find($nurse->service_id);
echo "Service: " . ($service ? $service->name : 'N/A') . "\n";

echo "\n--- Doctors in this Service ---\n";
$doctors = User::where('service_id', $nurse->service_id)
    ->whereIn('role', ['doctor', 'internal_doctor'])
    ->get();

foreach ($doctors as $doctor) {
    echo "Dr. " . $doctor->name . " (ID: " . $doctor->id . ")\n";
    echo "  Status: " . ($doctor->is_active ? 'Active' : 'Inactive') . "\n";
    // Check availabilities
    $dayName = strtolower(\Carbon\Carbon::now()->locale('en')->isoFormat('dddd'));
    $hasAvailability = $doctor->availabilities()->where('day_of_week', $dayName)->where('is_active', true)->exists();
    echo "  Available today ($dayName): " . ($hasAvailability ? 'Yes' : 'No') . "\n";
}

echo "\n--- Recent Appointments (Today) ---\n";
$appointments = Appointment::where('hospital_id', $nurse->hospital_id)
    ->where('service_id', $nurse->service_id)
    ->whereDate('appointment_datetime', now())
    ->get();

foreach ($appointments as $apt) {
    echo "Apt ID: " . $apt->id . " - Patient: " . ($apt->patient ? $apt->patient->name : 'N/A') . "\n";
    echo "  Status: " . $apt->status . "\n";
    echo "  Assigned Doctor ID: " . ($apt->doctor_id ?? 'NULL') . "\n";
    if ($apt->doctor_id) {
        $doc = User::find($apt->doctor_id);
        echo "  Doctor Service ID: " . ($doc ? $doc->service_id : 'N/A') . "\n";
        echo "  Match Nurse Service? " . (($doc && $doc->service_id == $nurse->service_id) ? 'Yes' : 'NO') . "\n";
    }
}
