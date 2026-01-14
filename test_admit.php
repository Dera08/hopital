<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\MedicalRecordController;
use App\Models\PatientVital;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

// Simulate the admit request
echo "Testing admit functionality...\n";

// Find a patient vital record that is not admitted
$record = PatientVital::where('status', '!=', 'admitted')->first();

if (!$record) {
    echo "No available patient vital record found for testing.\n";
    exit(1);
}

echo "Found patient vital record ID: {$record->id}\n";

// Find an available room
$room = Room::where('status', 'available')->first();

if (!$room) {
    echo "No available room found for testing.\n";
    exit(1);
}

echo "Found available room ID: {$room->id}\n";

// Create a fake request
$request = new Request();
$request->merge(['room_id' => $room->id]);

// Create controller instance
$controller = new MedicalRecordController();

try {
    // Call the admit method
    $response = $controller->admit($request, $record->id);

    echo "Admit method executed successfully!\n";
    echo "Response: " . $response . "\n";

    // Check if admission was created
    $admission = \App\Models\Admission::where('patient_id', $record->patient_id)->latest()->first();

    if ($admission) {
        echo "Admission created successfully with ID: {$admission->id}\n";
        echo "Admission type: {$admission->admission_type}\n";
        echo "Admission reason: {$admission->admission_reason}\n";
    } else {
        echo "Admission was not created.\n";
    }

} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Test completed successfully!\n";
