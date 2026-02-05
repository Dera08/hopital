<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PatientVital;
use App\Models\Admission;
use App\Models\Appointment;

$id = 10;
$record = PatientVital::with(['service', 'patient'])->find($id);

if (!$record) {
    die("Record $id not found.\n");
}

echo "--- RECORD $id ---\n";
echo "Patient: {$record->patient_name} (IPU: {$record->patient_ipu})\n";
echo "Temp: '{$record->temperature}'\n";
echo "BP: '{$record->blood_pressure}'\n";
echo "Service ID: {$record->service_id}\n";
echo "Service Name: '{$record->service->name}'\n";

echo "\n--- VIEW LOGIC CHECK ---\n";
$serviceName = $record->service?->name ?? 'Général';
echo "Used Service Name: '$serviceName'\n";

$serviceConfig = ['Urgences', 'Pédiatrie', 'Cardiologie', 'Maternité', 'Chirurgie'];
foreach ($serviceConfig as $key) {
    if (stripos($serviceName, $key) !== false) {
        echo "MATCH FOUND: $key\n";
    }
}

echo "\n--- APPOINTMENT CHECK ---\n";
// Try to find an appointment for this patient today
$appt = Appointment::where('hospital_id', $record->hospital_id)
    ->whereHas('patient', function($q) use ($record) {
        $q->where('ipu', $record->patient_ipu);
    })
    ->whereDate('updated_at', $record->created_at->toDateString()) // Approximate
    ->with('prestations')
    ->latest()
    ->first();

if ($appt) {
    echo "Found Appointment ID: {$appt->id}\n";
    echo "Prestations: " . $appt->prestations->pluck('name')->implode(', ') . "\n";
} else {
    echo "No matching appointment found.\n";
}
