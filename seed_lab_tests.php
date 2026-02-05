<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;

$labTestsConfig = [
    'Urgences' => [
        'quick_tests' => ['Troponine', 'Gaz du sang', 'Radio Thorax', 'TDM Cérébral', 'Lactates', 'D-Dimères'],
        'categories' => [
            'Biologie' => ['Troponine', 'Gaz du sang', 'Lactates', 'D-Dimères', 'NFS', 'CRP'],
            'Imagerie' => ['Radio Thorax', 'TDM Cérébral', 'Échographie Abdominale'],
        ]
    ],
    
    'Pédiatrie' => [
        'quick_tests' => ['TDR Palu', 'NFS', 'CRP', 'Glycémie', 'Radio Thorax'],
        'categories' => [
            'Biologie' => ['TDR Palu', 'NFS', 'CRP', 'Glycémie', 'Ionogramme', 'Bilan Hépatique'],
            'Imagerie' => ['Radio Thorax', 'Échographie Abdominale'],
        ]
    ],
    
    'Cardiologie' => [
        'quick_tests' => ['Écho-cœur', 'ECG', 'Bilan Lipidique', 'Troponine', 'BNP'],
        'categories' => [
            'Biologie' => ['Troponine', 'BNP', 'Bilan Lipidique', 'Glycémie', 'HbA1c'],
            'Imagerie' => ['Écho-cœur', 'ECG', 'Holter 24h', 'Épreuve d\'effort', 'Coronarographie'],
        ]
    ],
    
    'Maternité' => [
        'quick_tests' => ['Échographie Obstétricale', 'Monitoring Fœtal', 'Bilan Pré-natal', 'Groupe Sanguin'],
        'categories' => [
            'Biologie' => ['Groupe Sanguin', 'Bilan Pré-natal', 'Glycémie', 'Protéinurie', 'Sérologies'],
            'Imagerie' => ['Échographie Obstétricale', 'Monitoring Fœtal', 'Doppler Ombilical'],
        ]
    ],
    
    'Chirurgie' => [
        'quick_tests' => ['Bilan Pré-opératoire', 'Groupe Sanguin', 'NFS', 'TP/TCA', 'Radio Thorax'],
        'categories' => [
            'Biologie' => ['Bilan Pré-opératoire', 'Groupe Sanguin', 'NFS', 'TP/TCA', 'Ionogramme', 'Créatininémie'],
            'Imagerie' => ['Radio Thorax', 'TDM Abdominale', 'Échographie'],
        ]
    ],
];

foreach ($labTestsConfig as $serviceName => $config) {
    $services = Service::where('name', 'LIKE', '%' . $serviceName . '%')->get();
    
    foreach ($services as $service) {
        // Fusionner avec la config existante
        $diagnosticConfig = $service->diagnostic_config ?? [];
        $diagnosticConfig['lab_tests'] = $config;
        
        $service->update(['diagnostic_config' => $diagnosticConfig]);
        echo "✅ Updated lab tests for service: {$service->name}\n";
    }
}

echo "\n🧪 All lab test configurations added successfully!\n";
