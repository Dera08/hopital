<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\PatientVital;

echo "--- Debugging Visibility for Dr. Kouassi ---\n";

// 1. Find Doctor
$doctor = User::where('name', 'like', '%Kouassi%')->first();
if ($doctor) {
    echo "Doctor: " . $doctor->name . " (ID: " . $doctor->id . ")\n";
    echo "Role: " . $doctor->role . "\n";
    echo "Service ID: " . $doctor->service_id . " (" . ($doctor->service->name ?? 'Unknown') . ")\n";
    echo "Hospital ID: " . $doctor->hospital_id . "\n";
} else {
    echo "❌ Doctor 'Kouassi' not found.\n";
}

// 2. Find Patient Record
$records = PatientVital::where('patient_name', 'like', '%Brou Emmanuel%')
    ->with('service')
    ->orderBy('created_at', 'desc')
    ->get();

if ($records->count() > 0) {
    foreach ($records as $record) {
        echo "\nRecord ID: " . $record->id . "\n";
        echo "Created At: " . $record->created_at . "\n";
        echo "Status: '" . $record->status . "'\n";
        echo "Assigned Doctor ID: " . ($record->doctor_id ?? 'NULL') . "\n";
        echo "Record Service ID: " . $record->service_id . " (" . ($record->service->name ?? 'Unknown') . ")\n";
        echo "Hospital ID: " . $record->hospital_id . "\n";
        
        // Simulation of visibility logic
        if ($doctor) {
            $visible = false;
            // Matches strict assignment
            if ($record->doctor_id == $doctor->id) $visible = true;
            // Matches unassigned in service
            if (is_null($record->doctor_id) && $record->service_id == $doctor->service_id) $visible = true;
            
            echo "Visible to Doctor? " . ($visible ? "YES" : "NO") . "\n";
            
            if (!$visible) {
                echo "Reason: ";
                if ($record->doctor_id && $record->doctor_id != $doctor->id) echo "Assigned to another doctor (ID {$record->doctor_id}). ";
                if ($record->service_id != $doctor->service_id) echo "Service mismatch. ";
                if (!is_null($record->doctor_id) && $record->service_id == $doctor->service_id) echo "Assigned to someone else in same service. ";
                echo "\n";
            }
        }
    }
} else {
    echo "❌ No records found for 'Brou Emmanuel'.\n";
}
