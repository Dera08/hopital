<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

// 1. Find the patient
$patient = Patient::where('ipu', 'PAT202676387')->first();
if (!$patient) {
    die("Patient not found");
}

// 2. Mock login
Auth::guard('patients')->login($patient);

// 3. Share necessary variables for the layout
View::share('errors', new \Illuminate\Support\ViewErrorBag);

// 4. Call the controller method logic directly or resolve the controller
$controller = app(\App\Http\Controllers\Patient\PatientPortalController::class);
$response = $controller->documents();

// 5. Get the content
$html = $response->render();

// 6. Save to file
file_put_contents('test_render_output.html', $html);

echo "Render complete. Check test_render_output.html\n";
