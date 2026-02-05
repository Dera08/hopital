<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Patient;
use App\Models\Admission;

echo "--- Checking Patient Status ---\n";

try {
    $admissions = Admission::where('status', 'active')->get();
    echo "Count: " . $admissions->count() . "\n";

    foreach ($admissions as $adm) {
        $pid = $adm->patient_id;
        echo "Adm #{$adm->id} -> Pat #{$pid}\n";
        
        $p = Patient::withoutGlobalScopes()->find($pid);
        if ($p) {
            echo "   Found: {$p->name}\n";
            echo "   Deleted At: " . ($p->deleted_at ?? 'NULL') . "\n";
            echo "   Hospital ID: " . $p->hospital_id . "\n";
        } else {
            echo "   NOT FOUND (even without scopes)\n";
        }
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
