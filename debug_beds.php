<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\PatientVital;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Service;

$user = User::where('name', 'like', '%Sidibé%')->first(); // Dr Sidibé
if (!$user) {
    // If not found by name, try fallback
    $user = User::where('role', 'doctor_lab')->first();
}

$record = PatientVital::with('service')->find(13); // Record 13

echo "--- User Info ---\n";
if ($user) {
    echo "Name: " . $user->name . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Service ID: " . ($user->service_id ?? 'NULL') . "\n";
} else {
    echo "User Dr Sidibé not found.\n";
}

echo "\n--- Record Info ---\n";
if ($record) {
    echo "ID: " . $record->id . "\n";
    echo "Service ID: " . ($record->service_id ?? 'NULL') . "\n";
    echo "Service Name: " . ($record->service->name ?? 'N/A') . "\n";
} else {
    echo "Record #13 not found.\n";
}

echo "\n--- Services with Beds ---\n";
$services = Service::all();
foreach ($services as $svc) {
    $bedCount = Bed::whereHas('room', function($q) use ($svc) {
        $q->where('service_id', $svc->id);
    })->count();
    $availableBedCount = Bed::whereHas('room', function($q) use ($svc) {
        $q->where('service_id', $svc->id);
    })->where('is_available', true)->count();

    if ($bedCount > 0) {
        echo "Service [{$svc->id}] {$svc->name}: $bedCount total beds, $availableBedCount available.\n";
    }
}

echo "\n--- Simulation ---\n";
if ($user) {
    $targetServiceIdUser = $user->service_id ?? null;
    echo "Filter by User Service ID ($targetServiceIdUser): " . Bed::whereHas('room', function($q) use ($targetServiceIdUser) {
        if ($targetServiceIdUser) $q->where('service_id', $targetServiceIdUser);
    })->where('is_available', true)->count() . " available beds.\n";
}

if ($record) {
    $targetServiceIdRecord = $record->service_id ?? null;
    echo "Filter by Record Service ID ($targetServiceIdRecord): " . Bed::whereHas('room', function($q) use ($targetServiceIdRecord) {
        if ($targetServiceIdRecord) $q->where('service_id', $targetServiceIdRecord);
    })->where('is_available', true)->count() . " available beds.\n";
}
