<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$apt = \App\Models\Appointment::find(19);
if ($apt) {
    echo "Apt 19: Hospital ID=" . $apt->hospital_id . ", Service ID=" . $apt->service_id . " (" . ($apt->service->name ?? 'N/A') . ")\n";
} else {
    echo "Apt 19 not found.\n";
}
