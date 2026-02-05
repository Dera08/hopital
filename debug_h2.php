<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$nurses = \App\Models\User::where('role', 'nurse')->get();
foreach($nurses as $n) {
    echo "Nurse: " . $n->name . " (Hosp: " . $n->hospital_id . ", Service: " . ($n->service->name ?? 'N/A') . " ID:" . $n->service_id . ")\n";
}

$h2_services = \App\Models\Service::where('hospital_id', 2)->get();
echo "\nHospital 2 Services:\n";
foreach($h2_services as $s) {
    echo "- " . $s->id . ": " . $s->name . "\n";
}
