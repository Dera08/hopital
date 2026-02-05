<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Patient;
use App\Models\Admission;

echo "--- Checking Patient Status ---\n";

$admissions = Admission::where('status', 'active')->get();

foreach ($admissions as $adm) {
    echo "Admission ID: " . $adm->id . "\n";
    echo "Patient ID: " . $adm->patient_id . "\n";
    
    // Normal lookup
    $p = Patient::find($adm->patient_id);
    echo "Patient found (normal)? " . ($p ? "YES ({$p->name})" : "NO") . "\n";
    
    // With Trashed
    $pTrashed = Patient::withTrashed()->find($adm->patient_id);
    echo "Patient found (withTrashed)? " . ($pTrashed ? "YES ({$pTrashed->name})" : "NO") . "\n";
    
    if ($pTrashed && $pTrashed->deleted_at) {
        echo "⚠️ PATIENT IS SOFT DELETED at " . $pTrashed->deleted_at . "\n";
    }
    
    // Check scopes (simulating controller)
    $admWithRel = Admission::with('patient')->find($adm->id);
    echo "Loaded via relation? " . ($admWithRel->patient ? "YES" : "NO") . "\n";
    echo "--------------------------\n";
}
