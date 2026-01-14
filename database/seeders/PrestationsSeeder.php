<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Prestation;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrestationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all hospitals
        $hospitals = Hospital::all();

        foreach ($hospitals as $hospital) {
            // Get services for this hospital
            $services = Service::where('hospital_id', $hospital->id)->get();

            foreach ($services as $service) {
                // Create consultation prestation for each service
                Prestation::updateOrCreate([
                    'hospital_id' => $hospital->id,
                    'service_id' => $service->id,
                    'name' => 'Consultation ' . $service->name,
                ], [
                    'code' => 'CONS-' . strtoupper(substr($service->name, 0, 3)) . '-' . $hospital->id,
                    'category' => 'consultation',
                    'price' => $this->getConsultationPrice($service->name),
                    'description' => 'Consultation médicale - ' . $service->name,
                    'is_active' => true,
                ]);

                // Create additional prestations based on service type
                $this->createServiceSpecificPrestations($hospital, $service);
            }
        }
    }

    /**
     * Get consultation price based on service type
     */
    private function getConsultationPrice(string $serviceName): float
    {
        $prices = [
            'Médecine générale' => 50.00,
            'Cardiologie' => 80.00,
            'Dermatologie' => 60.00,
            'Gynécologie' => 70.00,
            'Ophtalmologie' => 65.00,
            'ORL' => 55.00,
            'Pédiatrie' => 45.00,
            'Psychiatrie' => 75.00,
            'Radiologie' => 90.00,
            'Urgences' => 40.00,
        ];

        return $prices[$serviceName] ?? 50.00; // Default price
    }

    /**
     * Create service-specific prestations
     */
    private function createServiceSpecificPrestations(Hospital $hospital, Service $service): void
    {
        $prestations = [];

        switch ($service->name) {
            case 'Médecine générale':
                $prestations = [
                    ['name' => 'Examen clinique complet', 'code' => 'EXAM-CLIN', 'price' => 25.00, 'category' => 'examen'],
                    ['name' => 'Prise de sang', 'code' => 'PREL-SANG', 'price' => 15.00, 'category' => 'examen'],
                    ['name' => 'Radio thorax', 'code' => 'RAD-THORAX', 'price' => 35.00, 'category' => 'examen'],
                ];
                break;

            case 'Cardiologie':
                $prestations = [
                    ['name' => 'ECG', 'code' => 'CARD-ECG', 'price' => 30.00, 'category' => 'examen'],
                    ['name' => 'Échocardiographie', 'code' => 'CARD-ECHO', 'price' => 120.00, 'category' => 'examen'],
                    ['name' => 'Holter ECG', 'code' => 'CARD-HOLTER', 'price' => 80.00, 'category' => 'examen'],
                ];
                break;

            case 'Dermatologie':
                $prestations = [
                    ['name' => 'Dermatoscopie', 'code' => 'DERM-SCOPE', 'price' => 40.00, 'category' => 'examen'],
                    ['name' => 'Cryothérapie', 'code' => 'DERM-CRYO', 'price' => 25.00, 'category' => 'soins'],
                    ['name' => 'Biopsie cutanée', 'code' => 'DERM-BIOPSY', 'price' => 85.00, 'category' => 'examen'],
                ];
                break;

            case 'Gynécologie':
                $prestations = [
                    ['name' => 'Examen gynécologique', 'code' => 'GYNE-EXAM', 'price' => 35.00, 'category' => 'examen'],
                    ['name' => 'Frottis cervico-vaginal', 'code' => 'GYNE-FROTTIS', 'price' => 20.00, 'category' => 'examen'],
                    ['name' => 'Échographie pelvienne', 'code' => 'GYNE-ECHO', 'price' => 70.00, 'category' => 'examen'],
                ];
                break;

            case 'Ophtalmologie':
                $prestations = [
                    ['name' => 'Fond d\'œil', 'code' => 'OPHT-FOND', 'price' => 25.00, 'category' => 'examen'],
                    ['name' => 'Tonometrie', 'code' => 'OPHT-TONO', 'price' => 15.00, 'category' => 'examen'],
                    ['name' => 'OCT', 'code' => 'OPHT-OCT', 'price' => 95.00, 'category' => 'examen'],
                ];
                break;

            case 'Pédiatrie':
                $prestations = [
                    ['name' => 'Examen pédiatrique', 'code' => 'PED-EXAM', 'price' => 30.00, 'category' => 'examen'],
                    ['name' => 'Vaccination', 'code' => 'PED-VACC', 'price' => 12.00, 'category' => 'soins'],
                    ['name' => 'Test de dépistage', 'code' => 'PED-TEST', 'price' => 18.00, 'category' => 'examen'],
                ];
                break;

            case 'Radiologie':
                $prestations = [
                    ['name' => 'Radio standard', 'code' => 'RAD-STD', 'price' => 25.00, 'category' => 'examen'],
                    ['name' => 'Scanner', 'code' => 'RAD-SCANNER', 'price' => 150.00, 'category' => 'examen'],
                    ['name' => 'IRM', 'code' => 'RAD-IRM', 'price' => 200.00, 'category' => 'examen'],
                ];
                break;

            case 'Urgences':
                $prestations = [
                    ['name' => 'Consultation urgence', 'code' => 'URG-CONS', 'price' => 25.00, 'category' => 'consultation'],
                    ['name' => 'Suture', 'code' => 'URG-SUTURE', 'price' => 45.00, 'category' => 'soins'],
                    ['name' => 'Plâtre', 'code' => 'URG-PLATRE', 'price' => 35.00, 'category' => 'soins'],
                ];
                break;
        }

        foreach ($prestations as $prestation) {
            Prestation::updateOrCreate([
                'hospital_id' => $hospital->id,
                'service_id' => $service->id,
                'name' => $prestation['name'],
            ], [
                'code' => $prestation['code'] . '-' . $hospital->id,
                'category' => $prestation['category'],
                'price' => $prestation['price'],
                'description' => $prestation['name'] . ' - ' . $service->name,
                'is_active' => true,
            ]);
        }
    }
}
