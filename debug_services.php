<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG SERVICES LOADING ===\n\n";

echo "Total Hospitals: " . \App\Models\Hospital::count() . "\n";
echo "Total Services: " . \App\Models\Service::count() . "\n\n";

$hospitals = \App\Models\Hospital::with('services')->get();

foreach ($hospitals as $hospital) {
    echo "Hospital: {$hospital->name} (ID: {$hospital->id})\n";
    echo "Services count: " . $hospital->services->count() . "\n";

    if ($hospital->services->count() > 0) {
        echo "Services:\n";
        foreach ($hospital->services as $service) {
            echo "  - {$service->name} (ID: {$service->id}, Price: {$service->consultation_price})\n";
        }
    } else {
        echo "  No services found for this hospital\n";
    }
    echo "\n";
}

// Check if services have consultation_price
echo "=== SERVICES WITH PRICES ===\n";
$services = \App\Models\Service::all();
foreach ($services as $service) {
    echo "{$service->name}: consultation_price = " . ($service->consultation_price ?? 'NULL') . "\n";
}
