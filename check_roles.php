<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Afficher tous les rôles distincts
$roles = DB::table('users')->select('role')->distinct()->pluck('role');

echo "Rôles trouvés dans la base de données:\n";
foreach ($roles as $role) {
    echo "  - $role\n";
}
