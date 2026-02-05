<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Patient;
use App\Models\PatientVital;
use App\Models\Admission;

$vital = PatientVital::withoutGlobalScopes()->find(13);
echo "Vital ID 13 Hospital ID: |" . ($vital ? $vital->hospital_id : 'NOT FOUND') . "|\n";

$admission = Admission::withoutGlobalScopes()->find(5);
echo "Admission ID 5 Hospital ID: |" . ($admission ? $admission->hospital_id : 'NOT FOUND') . "|\n";

$patient = Patient::withTrashed()->withoutGlobalScopes()->find(2);
echo "Patient ID 2 Hospital ID: |" . ($patient ? $patient->hospital_id : 'NOT FOUND') . "|\n";
