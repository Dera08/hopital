<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\PatientVital;
$record = PatientVital::find(13);
echo "Record #13 Status: " . ($record ? $record->status : 'NOT FOUND') . "\n";
