<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;

// Récupérer tous les hôpitaux
$hospitals = Hospital::all();

foreach ($hospitals as $hospital) {
    echo "🏥 Traitement de l'hôpital: {$hospital->name}\n";
    
    // 1. Créer le service Laboratoire s'il n'existe pas
    $labService = Service::firstOrCreate(
        [
            'hospital_id' => $hospital->id,
            'name' => 'Laboratoire'
        ],
        [
            'code' => 'LAB-' . $hospital->id,
            'description' => 'Service de biologie médicale et imagerie',
            'consultation_price' => 0,
            'form_config' => [],
            'diagnostic_config' => [
                'lab_tests' => [
                    'quick_tests' => ['NFS', 'CRP', 'Glycémie', 'Créatininémie', 'Transaminases'],
                    'categories' => [
                        'Hématologie' => ['NFS', 'VS', 'TP/TCA', 'Groupe Sanguin'],
                        'Biochimie' => ['Glycémie', 'Créatininémie', 'Urée', 'Transaminases', 'Bilan Lipidique'],
                        'Microbiologie' => ['TDR Palu', 'ECBU', 'Hémoculture', 'Coproculture'],
                        'Imagerie' => ['Radio', 'Échographie', 'TDM', 'IRM'],
                    ]
                ]
            ],
            'admission_config' => null,
        ]
    );

    echo "  ✅ Service Laboratoire créé/trouvé (ID: {$labService->id})\n";

    // 2. Créer un technicien de laboratoire
    $labTech = User::firstOrCreate(
        [
            'email' => "lab.tech@{$hospital->slug}.com",
            'hospital_id' => $hospital->id,
        ],
        [
            'name' => 'Technicien Laboratoire',
            'password' => Hash::make('password'),
            'role' => 'lab_technician',
            'service_id' => $labService->id,
            'is_active' => true,
            'phone' => '+225 07 00 00 00',
        ]
    );

    echo "  ✅ Technicien de laboratoire créé: {$labTech->email} (password: password)\n";

    // 3. Créer un médecin biologiste (optionnel)
    $labDoctor = User::firstOrCreate(
        [
            'email' => "dr.biologiste@{$hospital->slug}.com",
            'hospital_id' => $hospital->id,
        ],
        [
            'name' => 'Dr. Biologiste',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'service_id' => $labService->id,
            'is_active' => true,
            'phone' => '+225 07 00 00 01',
            'registration_number' => 'BIO' . str_pad($hospital->id, 4, '0', STR_PAD_LEFT),
        ]
    );

    echo "  ✅ Médecin biologiste créé: {$labDoctor->email} (password: password)\n";
    echo "\n";
}

echo "🎉 Configuration du laboratoire terminée pour tous les hôpitaux!\n";
echo "\n📋 Rôle ajouté: 'lab_technician'\n";
echo "   Accès au dashboard: /lab/dashboard\n";
