<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$hospitalId = 2; // Based on the error log earlier
$walkins = \App\Models\WalkInConsultation::where('hospital_id', $hospitalId)
    ->with('patient')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Total Walk-ins for Hosp 2: " . $walkins->count() . "\n";
foreach($walkins as $w) {
    echo "ID: " . $w->id . " | Patient: " . ($w->patient->name ?? 'N/A') . " | Status: " . $w->status . " | Created: " . $w->created_at . " | Transaction: " . $w->payment_transaction_id . "\n";
}

$konan_patient = \App\Models\Patient::where('name', 'like', '%konan%')->get();
echo "\nPatients named Konan: " . $konan_patient->count() . "\n";
foreach($konan_patient as $p) {
    echo "Patient ID: " . $p->id . " | Name: " . $p->name . " | Phone: " . $p->phone . "\n";
}
