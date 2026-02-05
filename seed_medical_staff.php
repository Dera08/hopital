<?php

use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$hospitalId = 1; // Basé sur les captures et le contexte

$medicalStaff = [
    'Cardiologue' => [
        ['name' => 'Dr. Kouassi Koffi', 'role' => 'doctor', 'email' => 'dr.koffi@hopital1.com'],
        ['name' => 'Inf. Yao Marie', 'role' => 'nurse', 'email' => 'yao.marie@hopital1.com'],
    ],
    'Pédiatrie' => [
        ['name' => 'Dr. Bamba Salimata', 'role' => 'doctor', 'email' => 'dr.bamba@hopital1.com'],
        ['name' => 'Inf. Konan Jean', 'role' => 'nurse', 'email' => 'konan.jean@hopital1.com'],
    ],
    'MATERNITE' => [
        ['name' => 'Dr. Traoré Fatoumata', 'role' => 'doctor', 'email' => 'dr.traore@hopital1.com'],
        ['name' => 'Inf. Touré Aminata', 'role' => 'nurse', 'email' => 'toure.aminata@hopital1.com'],
    ],
    'Medecin générale' => [
        ['name' => 'Dr. N\'Guessan Paul', 'role' => 'doctor', 'email' => 'dr.nguessan@hopital1.com'],
        ['name' => 'Inf. Gnamien Lucie', 'role' => 'nurse', 'email' => 'gnamien.lucie@hopital1.com'],
    ],
    'Laboratoire' => [
        ['name' => 'Dr. Sidibé Moussa', 'role' => 'doctor_lab', 'email' => 'sidibe.moussa@hopital1.com'],
        ['name' => 'Tech. Bakayoko Abiba', 'role' => 'lab_technician', 'email' => 'bakayoko.abiba@hopital1.com'],
    ]
];

foreach ($medicalStaff as $serviceName => $staffMembers) {
    $service = Service::where('hospital_id', $hospitalId)
                      ->where('name', 'like', "%$serviceName%")
                      ->first();
    
    if (!$service) {
        echo "Service non trouvé : $serviceName\n";
        continue;
    }

    foreach ($staffMembers as $member) {
        $user = User::where('email', $member['email'])->first();
        if (!$user) {
            User::create([
                'hospital_id' => $hospitalId,
                'service_id' => $service->id,
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => Hash::make('password123'),
                'role' => $member['role'],
                'is_active' => true,
                'registration_number' => 'REG-' . rand(1000, 9999)
            ]);
            echo "Utilisateur créé : {$member['name']} ({$member['role']}) dans {$service->name}\n";
        } else {
            echo "Utilisateur déjà existant : {$member['email']}\n";
        }
    }
}
