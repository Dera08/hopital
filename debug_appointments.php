<?php
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Patient;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$user = User::where('role', 'nurse')->whereHas('service', function($q) { $q->where('name', 'like', '%Pédia%'); })->first();

if (!$user) {
    echo "Nurse in Pédiatrie not found.\n";
    exit;
}

echo "Nurse: " . $user->name . " (ID: " . $user->id . ", Service ID: " . $user->service_id . ")\n";

$todayByService = Appointment::where('hospital_id', $user->hospital_id)
    ->where('service_id', $user->service_id)
    ->whereDate('appointment_datetime', now()->toDateString())
    ->get();

echo "\nAppointments for today in this service (" . $todayByService->count() . "):\n";
foreach ($todayByService as $apt) {
    echo "- ID: " . $apt->id . ", Status: " . $apt->status . ", Type: " . $apt->type . ", Patient: " . ($apt->patient->name ?? 'N/A') . "\n";
}

$allTodayPaid = Appointment::where('hospital_id', $user->hospital_id)
    ->where('status', 'paid')
    ->whereDate('appointment_datetime', now()->toDateString())
    ->get();

echo "\nALL Paid Appointments for today in the hospital (" . $allTodayPaid->count() . "):\n";
foreach ($allTodayPaid as $apt) {
    echo "- ID: " . $apt->id . ", Service ID: " . $apt->service_id . " (" . ($apt->service->name ?? 'N/A') . "), Patient: " . ($apt->patient->name ?? 'N/A') . "\n";
}
