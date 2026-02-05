<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$p = \App\Models\Patient::where('ipu', 'IPU-697BE435463CE')->first();
if ($p) {
    echo "Patient: " . $p->name . "\n";
    foreach($p->appointments as $a) {
        echo "  Apt ID=" . $a->id . ", Service=" . ($a->service->name ?? 'N/A') . " (ID=" . $a->service_id . ")\n";
    }
}
