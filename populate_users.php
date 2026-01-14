<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Service;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Starting to populate users table...\n";

// Sample users data
$users = [
    [
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'is_active' => true,
        'phone' => '+225 01 02 03 04',
    ],
    [
        'name' => 'Dr. John Doe',
        'email' => 'doctor@example.com',
        'password' => Hash::make('password'),
        'role' => 'doctor',
        'is_active' => true,
        'phone' => '+225 05 06 07 08',
    ],
    [
        'name' => 'Nurse Jane',
        'email' => 'nurse@example.com',
        'password' => Hash::make('password'),
        'role' => 'nurse',
        'is_active' => true,
        'phone' => '+225 09 10 11 12',
    ],
    [
        'name' => 'Cashier Bob',
        'email' => 'cashier@example.com',
        'password' => Hash::make('password'),
        'role' => 'cashier',
        'is_active' => true,
        'phone' => '+225 13 14 15 16',
    ],
];

// Get first hospital and service if available
$hospital = Hospital::first();
$service = Service::first();

foreach ($users as $userData) {
    $userData['hospital_id'] = $hospital ? $hospital->id : null;
    if (in_array($userData['role'], ['doctor', 'nurse'])) {
        $userData['service_id'] = $service ? $service->id : null;
    }

    User::create($userData);
    echo "Created user: {$userData['email']}\n";
}

echo "Users table populated successfully!\n";
echo "You can now run: SELECT * FROM `users` to see the data.\n";
