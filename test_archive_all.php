<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PatientVital;

// Find a record for Kouassi Ange
$record = PatientVital::where('patient_name', 'Kouassi Ange')->first();

if ($record) {
    echo 'Archiving all records for ' . $record->patient_name . ' (IPU: ' . $record->patient_ipu . ')' . PHP_EOL;

    // Archive all records for this patient
    $updated = PatientVital::where('patient_name', $record->patient_name)
        ->where('patient_ipu', $record->patient_ipu)
        ->update(['status' => 'archived']);

    echo 'Updated ' . $updated . ' records.' . PHP_EOL;

    // Check active records
    $activeRecords = PatientVital::where('status', 'active')
        ->orWhereNull('status')
        ->get();

    $kouassiActive = $activeRecords->where('patient_name', 'Kouassi Ange')->first();

    if ($kouassiActive) {
        echo 'ERROR: Kouassi Ange is still in active list.' . PHP_EOL;
    } else {
        echo 'SUCCESS: Kouassi Ange is no longer in active list.' . PHP_EOL;
    }
} else {
    echo 'Patient Kouassi Ange not found.' . PHP_EOL;
}
