<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::where('email', 'aicha@saintjean.com')->first();
if ($u) {
    echo "ID: " . $u->id . "\n";
    echo "Name: " . $u->name . "\n";
    echo "HospID: " . ($u->hospital_id ?? 'NULL') . "\n";
    echo "SrvID: " . ($u->service_id ?? 'NULL') . "\n";
    echo "Role: " . $u->role . "\n";
    echo "Email: " . $u->email . "\n";
} else {
    echo "User not found\n";
}
