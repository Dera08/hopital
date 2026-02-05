<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PatientVital;

echo "--- CLEANING UP DUPLICATES FOR BROU EMMANUEL ---\n";

// Find all active records for Brou
$records = PatientVital::where('patient_name', 'like', '%Brou Emmanuel%')
    ->where('status', 'active')
    ->orderBy('created_at', 'desc')
    ->get();

if ($records->count() > 1) {
    echo "Found " . $records->count() . " active records.\n";
    // Keep the latest one, archive the rest
    $latest = $records->first();
    echo "Keeping Latest ID: {$latest->id} (Date: {$latest->created_at})\n";
    
    $duplicates = $records->slice(1);
    foreach ($duplicates as $dup) {
        echo "Archiving Duplicate ID: {$dup->id} (Date: {$dup->created_at})\n";
        $dup->update(['status' => 'archived']);
    }
} else {
    echo "No duplicates found to clean.\n";
}
