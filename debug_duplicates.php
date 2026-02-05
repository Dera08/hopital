<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PatientVital;

echo "--- INSPECTING DUPLICATES FOR BROU EMMANUEL ---\n";

$records = PatientVital::where('patient_name', 'like', '%Brou Emmanuel%')
    // ->whereDate('created_at', now()->toDateString()) // Check all history
    ->get();

echo "Found " . $records->count() . " records for today.\n";

foreach ($records as $r) {
    echo "ID: {$r->id}\n";
    echo "  IPU: '{$r->patient_ipu}'\n";
    echo "  Created: {$r->created_at}\n";
    echo "  Doctor ID: {$r->doctor_id}\n";
    echo "  Service ID: {$r->service_id}\n";
    echo "  Status: {$r->status}\n";
    echo "------------------------\n";
}
