<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PatientVital;

$record = PatientVital::where('patient_name', 'Kouassi Ange')->first();

if ($record) {
    echo 'Current status of Kouassi Ange: ' . $record->status . PHP_EOL;
    echo 'ID: ' . $record->id . PHP_EOL;
} else {
    echo 'Patient Kouassi Ange not found.' . PHP_EOL;
}

// Check all records
$all = PatientVital::all();
echo 'All records:' . PHP_EOL;
foreach ($all as $r) {
    echo $r->patient_name . ': ' . $r->status . PHP_EOL;
}
