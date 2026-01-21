<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo '=== CHECKING SERVICES STATUS ===' . PHP_EOL;
$services = App\Models\Service::all();
echo 'Total services: ' . $services->count() . PHP_EOL;

foreach($services as $service) {
    echo 'Service: ' . $service->name . ' (ID: ' . $service->id . ', Hospital: ' . $service->hospital_id . ', Active: ' . ($service->is_active ? 'YES' : 'NO') . ')' . PHP_EOL;
}

echo PHP_EOL . '=== CHECKING PRESTATIONS STATUS ===' . PHP_EOL;
$prestations = App\Models\Prestation::all();
echo 'Total prestations: ' . $prestations->count() . PHP_EOL;

foreach($prestations as $prestation) {
    echo 'Prestation: ' . $prestation->name . ' (ID: ' . $prestation->id . ', Hospital: ' . $prestation->hospital_id . ', Active: ' . ($prestation->is_active ? 'YES' : 'NO') . ')' . PHP_EOL;
}
