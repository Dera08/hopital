<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Service;
use App\Models\PatientVital;

echo "--- DEEP DEBUG NURSE ASSIGNMENT ---\n";

// 1. Get Nurse
$nurse = User::where('role', 'nurse')->first();
if (!$nurse) die("No nurse found.\n");
echo "Nurse: {$nurse->name} (ID: {$nurse->id})\n";
echo "Hospital ID: {$nurse->hospital_id}\n";
echo "Service ID: {$nurse->service_id}\n";

$service = Service::find($nurse->service_id);
echo "Service Name: " . ($service ? $service->name : 'N/A') . "\n";

// 2. Check Strict Service Query (Round Robin Candidates)
$dayName = strtolower(\Carbon\Carbon::now()->locale('en')->isoFormat('dddd'));
echo "Today is: $dayName\n";

$candidatesQuery = User::where('hospital_id', $nurse->hospital_id)
    ->where('service_id', $nurse->service_id)
    ->whereIn('role', ['doctor', 'internal_doctor'])
    ->where('is_active', true);

$totalDoctorsInService = $candidatesQuery->count();
echo "Total Active Doctors in Service {$nurse->service_id}: $totalDoctorsInService\n";

// 3. Check Availability constraint
$candidatesWithAvailability = $candidatesQuery->whereHas('availabilities', function($query) use ($dayName) {
    $query->where('day_of_week', $dayName)
          ->where('is_active', true);
})->get();

echo "Doctors with Availability for '$dayName': " . $candidatesWithAvailability->count() . "\n";

if ($candidatesWithAvailability->count() == 0 && $totalDoctorsInService > 0) {
    echo "!!! PROBLEM: Doctors exist but none are available today ($dayName).\n";
    echo "This is likely why assignment is failing.\n";
    
    // List doctors without availability to verify
    $allDocs = User::where('hospital_id', $nurse->hospital_id)
        ->where('service_id', $nurse->service_id)
        ->whereIn('role', ['doctor', 'internal_doctor'])
        ->get();
        
    foreach($allDocs as $d) {
        echo "- Dr. {$d->name} (Service: {$d->service_id})\n";
        echo "  Availabilities: " . $d->availabilities()->count() . "\n";
        foreach($d->availabilities as $a) {
            echo "    - {$a->day_of_week}: " . ($a->is_active ? 'Active' : 'Inactive') . "\n";
        }
    }
} else {
    foreach ($candidatesWithAvailability as $doc) {
        $patientCount = PatientVital::where('doctor_id', $doc->id)
            ->whereDate('created_at', now()->toDateString())
            ->where('status', '!=', 'archived')
            ->count();
        echo "  - Dr. {$doc->name} has $patientCount patients today.\n";
    }
}
