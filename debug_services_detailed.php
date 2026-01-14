<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Hospital;
use App\Models\Service;

echo "=== DEBUG SERVICES LOADING ===\n\n";

echo "Total hospitals: " . Hospital::count() . "\n";
echo "Total services: " . Service::count() . "\n\n";

echo "Hospitals with services:\n";
$hospitals = Hospital::with('services')->get();
foreach ($hospitals as $hospital) {
    echo "- {$hospital->name} (ID: {$hospital->id}): {$hospital->services->count()} services\n";
    foreach ($hospital->services as $service) {
        echo "  * {$service->name} (ID: {$service->id}, hospital_id: {$service->hospital_id}, is_active: " . ($service->is_active ? 'true' : 'false') . ")\n";
    }
    echo "\n";
}

echo "Services without hospital:\n";
$servicesWithoutHospital = Service::whereNull('hospital_id')->get();
foreach ($servicesWithoutHospital as $service) {
    echo "- {$service->name} (ID: {$service->id})\n";
}

echo "\n=== CONTROLLER SIMULATION ===\n";

$patient = \App\Models\Patient::first();
if ($patient) {
    echo "Patient hospital_id: {$patient->hospital_id}\n";

    $hospitals = Hospital::where('is_active', true)->get();
    echo "Active hospitals: {$hospitals->count()}\n";

    $services = [];
    foreach ($hospitals as $hospital) {
        $hospitalServices = $hospital->services()
            ->where('is_active', true)
            ->get();

        $servicesWithPrice = [];
        foreach ($hospitalServices as $service) {
            $consultationPrestation = \App\Models\Prestation::where('service_id', $service->id)
                ->where('category', 'consultation')
                ->where('is_active', true)
                ->first();

            $consultationPrice = $consultationPrestation ? $consultationPrestation->price : 0;

            $servicesWithPrice[] = [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $consultationPrice,
            ];
        }

        $services[$hospital->id] = $servicesWithPrice;
    }

    echo "Services array for controller:\n";
    foreach ($services as $hospitalId => $hospitalServices) {
        echo "Hospital $hospitalId: " . count($hospitalServices) . " services\n";
        foreach ($hospitalServices as $service) {
            echo "  - {$service['name']} (price: {$service['price']})\n";
        }
    }
} else {
    echo "No patients found\n";
}
