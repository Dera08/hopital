<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simulate the controller logic
$patient = null; // Simulating patient
$hospitals = \App\Models\Hospital::where('is_active', true)->get();

$servicesAndPrestations = [];

foreach ($hospitals as $hospital) {
    $hospitalServices = \App\Models\Service::where('hospital_id', $hospital->id)
        ->where('is_active', true)
        ->get();

    $mergedList = [];

    foreach ($hospitalServices as $service) {
        $mergedList[] = [
            'id'    => $service->id,
            'name'  => $service->name,
            'price' => $service->consultation_price ?? 0,
            'type'  => 'service'
        ];
    }

    $hospitalPrestations = \App\Models\Prestation::where('hospital_id', $hospital->id)
        ->where('is_active', true)
        ->get();

    foreach ($hospitalPrestations as $prestation) {
        $mergedList[] = [
            'id'    => $prestation->id,
            'name'  => $prestation->name,
            'price' => $prestation->price ?? 0,
            'type'  => 'prestation'
        ];
    }

    $servicesAndPrestations[$hospital->id] = $mergedList;
}

echo '=== HOSPITALS ===' . PHP_EOL;
foreach ($hospitals as $hospital) {
    echo $hospital->id . ': ' . $hospital->name . PHP_EOL;
}

echo PHP_EOL . '=== JAVASCRIPT DATA THAT WOULD BE PASSED ===' . PHP_EOL;
echo 'const servicesData = ' . json_encode($servicesAndPrestations, JSON_PRETTY_PRINT) . ';' . PHP_EOL;

echo PHP_EOL . '=== CHECKING IF HOSPITAL 1 HAS DATA ===' . PHP_EOL;
if (isset($servicesAndPrestations[1])) {
    echo 'Hospital 1 has ' . count($servicesAndPrestations[1]) . ' services/prestations' . PHP_EOL;
    foreach ($servicesAndPrestations[1] as $item) {
        echo '  - ' . $item['name'] . ' (ID: ' . $item['id'] . ')' . PHP_EOL;
    }
} else {
    echo 'Hospital 1 has no data!' . PHP_EOL;
}
