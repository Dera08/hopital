<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$names = ['anne anne', 'koki koki', 'grace de la croix'];
foreach($names as $name) {
    $p = \App\Models\Patient::where('name', 'like', "%$name%")->first();
    if ($p) {
        echo "Patient: " . $p->name . " (IPU: " . $p->ipu . ", ID: " . $p->id . ", Hospital ID: " . $p->hospital_id . ")\n";
        $apts = \App\Models\Appointment::where('patient_id', $p->id)->get();
        foreach($apts as $apt) {
            echo "  Appointment: ID=" . $apt->id . ", Service=" . ($apt->service->name ?? 'N/A') . " (ID=" . $apt->service_id . "), Status=" . $apt->status . ", Date=" . $apt->appointment_datetime . "\n";
        }
        $adm = \App\Models\Admission::where('patient_id', $p->id)->get();
        foreach($adm as $a) {
            echo "  Admission: ID=" . $a->id . ", Service=" . ($a->service->name ?? 'N/A') . "\n";
        }
    } else {
        echo "Patient $name not found.\n";
    }
}
