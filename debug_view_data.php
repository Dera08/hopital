<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo '=== TESTING CONTROLLER DATA ===' . PHP_EOL;

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

echo 'Hospitals found: ' . $hospitals->count() . PHP_EOL;
echo 'ServicesAndPrestations array keys: ' . implode(', ', array_keys($servicesAndPrestations)) . PHP_EOL;

foreach ($servicesAndPrestations as $hospitalId => $services) {
    echo "Hospital $hospitalId has " . count($services) . " services/prestations:" . PHP_EOL;
    foreach ($services as $service) {
        echo "  - {$service['name']} (ID: {$service['id']}, Price: {$service['price']})" . PHP_EOL;
    }
}

echo PHP_EOL . '=== JSON OUTPUT FOR JAVASCRIPT ===' . PHP_EOL;
echo json_encode($servicesAndPrestations, JSON_PRETTY_PRINT);
