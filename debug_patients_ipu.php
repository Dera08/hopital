<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$ipus = ['IPU-697BE435463CE', 'IPU-697B7AD0B78E6', 'PAT202677908'];
foreach($ipus as $ipu) {
    $p = \App\Models\Patient::where('ipu', $ipu)->first();
    if ($p) {
        echo "Patient: " . $p->name . " " . $p->first_name . " (IPU: " . $p->ipu . ", ID: " . $p->id . ", Hospital ID: " . $p->hospital_id . ")\n";
        $apts = \App\Models\Appointment::where('patient_id', $p->id)->get();
        foreach($apts as $apt) {
            echo "  Appointment: ID=" . $apt->id . ", Service=" . ($apt->service->name ?? 'N/A') . " (ID=" . $apt->service_id . "), Status=" . $apt->status . ", Date=" . $apt->appointment_datetime . "\n";
        }
    } else {
        echo "Patient with IPU $ipu not found.\n";
    }
}
