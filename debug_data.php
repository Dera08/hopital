<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Hospital;

echo "--- HOSPITALS ---\n";
foreach (Hospital::all() as $h) {
    echo "ID: {$h->id}, Name: {$h->name}, Slug: {$h->slug}\n";
}

echo "\n--- ADMIN USERS ---\n";
$admins = User::where('role', 'admin')->get();
foreach ($admins as $a) {
    echo "ID: {$a->id}, Name: {$a->name}, Email: {$a->email}, Hospital ID: " . ($a->hospital_id ?? 'NULL') . "\n";
}

echo "\n--- SELECTED USERS ---\n";
$users = User::where('name', 'like', '%(H2)%')->get();
foreach ($users as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, Hospital ID: " . ($u->hospital_id ?? 'NULL') . "\n";
}
