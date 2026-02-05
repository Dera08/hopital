<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Patient;
use App\Models\PatientVital;
use App\Models\Prescription;
use App\Models\Admission;

$ipu = 'PAT202604780';
$patient = Patient::withTrashed()->withoutGlobalScopes()->where('ipu', $ipu)->first();

echo "Patient: " . ($patient ? $patient->name : 'NOT FOUND') . "\n";
if ($patient) {
    echo "Hospital ID: " . $patient->hospital_id . "\n";
    echo "Vitals Count: " . PatientVital::withoutGlobalScopes()->where('patient_ipu', $ipu)->count() . "\n";
    echo "Prescriptions Count: " . Prescription::withoutGlobalScopes()->where('patient_id', $patient->id)->count() . "\n";
    
    $admission = Admission::withoutGlobalScopes()->where('patient_id', $patient->id)->where('status', 'active')->first();
    echo "Active Admission ID: " . ($admission ? $admission->id : 'NONE') . "\n";
    if ($admission) {
        $signes = $admission->derniersSignes;
        echo "Derniers Signes: " . ($signes ? "Found (ID: {$signes->id}, Temp: {$signes->temperature})" : "NOT FOUND") . "\n";
    }
}
