<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PatientVital;

$id = 13;
$record = PatientVital::find($id);

if (!$record) {
    die("Record $id not found.\n");
}

echo "--- RECORD $id ---\n";
echo "Patient: {$record->patient_name}\n";
echo "Temp: '{$record->temperature}' (Raw: " . var_export($record->temperature, true) . ")\n";
echo "BP: '{$record->blood_pressure}'\n";
echo "Pulse: '{$record->pulse}'\n";
echo "Weight: '{$record->weight}'\n";
echo "Reason: '{$record->reason}'\n";
echo "Created At: {$record->created_at}\n";
