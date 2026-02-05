<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$apts = \App\Models\Appointment::where('service_id', 8)->where('status', 'paid')->get();
foreach($apts as $a) {
    echo $a->id . ": " . $a->appointment_datetime . " (Paid at: " . $a->updated_at . ")\n";
}
