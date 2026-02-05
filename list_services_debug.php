<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;

$services = Service::with('prestations')->get();
foreach ($services as $s) {
    echo "Service: {$s->name} (ID: {$s->id})\n";
    foreach ($s->prestations as $p) {
        echo "  - Prestation: {$p->name} (ID: {$p->id})\n";
    }
}
