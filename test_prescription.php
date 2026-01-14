<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing prescription creation...\n";

try {
    // Check if there's an existing patient
    $patient = App\Models\Patient::first();

    if ($patient) {
        echo "Found existing patient: {$patient->name}\n";
    } else {
        echo "No patients found. Creating test patient...\n";
        $patient = App\Models\Patient::create([
            'hospital_id' => 1,
            'ipu' => 'TEST001',
            'name' => 'Test Patient',
            'first_name' => 'Test',
            'dob' => '1990-01-01',
            'gender' => 'Homme',
            'phone' => '123456789',
            'email' => 'test@example.com',
            'is_active' => true,
        ]);
        echo "Test patient created with ID: {$patient->id}\n";
    }

    // Test prescription creation
    echo "Creating prescription...\n";
    $prescription = App\Models\Prescription::create([
        'patient_id' => $patient->id,
        'doctor_id' => 1,
        'hospital_id' => 1,
        'medication' => 'Test Medication',
        'dosage' => '10mg',
        'frequency' => '1x/day',
        'start_date' => now(),
        'instructions' => 'Take once daily',
        'is_signed' => false,
        'status' => 'active',
        'allergy_checked' => false,
    ]);

    echo "✅ Prescription created successfully with ID: {$prescription->id}\n";
    echo "✅ All columns are working correctly!\n";

    // Test prescription update
    echo "Testing prescription update...\n";
    $prescription->update([
        'instructions' => 'Take once daily with food',
        'is_signed' => true,
        'signed_at' => now(),
    ]);
    echo "✅ Prescription updated successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "🎉 All tests passed! Prescription functionality is working correctly.\n";
