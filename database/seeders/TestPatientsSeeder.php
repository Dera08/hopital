<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{Patient, Admission, ClinicalObservation, User, Room};

class TestPatientsSeeder extends Seeder
{
    public function run(): void
    {
        // Get a doctor for assignments
        $medecin = User::where('role', 'doctor')->first();
        if (!$medecin) {
            $this->command->error('Aucun médecin trouvé. Veuillez créer des médecins d\'abord.');
            return;
        }

        // Get rooms for different services
        $room1 = Room::where('service_id', $medecin->service_id)->first();
        $room2 = Room::where('service_id', $medecin->service_id)->skip(1)->first() ?? $room1;
        $room3 = Room::where('service_id', $medecin->service_id)->skip(2)->first() ?? $room1;

        if (!$room1) {
            $this->command->error('Aucune chambre trouvée. Veuillez créer des chambres d\'abord.');
            return;
        }

        // Patient 1: Aminata KOUASSI - CRITIQUE
        $p1 = Patient::create([
            'ipu' => Patient::generateIpu(),
            'name' => 'KOUASSI',
            'first_name' => 'Aminata',
            'dob' => '1975-05-15',
            'gender' => 'Femme',
            'phone' => '+225 01 23 45 67 89',
            'email' => 'aminata.kouassi@test.ci',
            'password' => Hash::make('password'),
            'address' => 'Cocody',
            'city' => 'Abidjan',
            'blood_group' => 'O+',
            'is_active' => true
        ]);

        $a1 = Admission::create([
            'patient_id' => $p1->id,
            'room_id' => $room1->id,
            'bed_number' => 'A',
            'doctor_id' => $medecin->id,
            'admission_date' => now()->subDays(2),
            'admission_type' => 'emergency',
            'status' => 'active',
            'alert_level' => 'critical',
            'admission_reason' => 'Insuffisance Cardiaque Aiguë - Œdème pulmonaire'
        ]);

        ClinicalObservation::create([
            'patient_id' => $p1->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '38.5',
            'unit' => '°C',
            'observation_datetime' => now(),
            'is_critical' => true,
            'notes' => 'Fièvre + Dyspnée + Œdème'
        ]);

        $this->command->info(" Patient CRITIQUE: Aminata KOUASSI ({$p1->ipu})");

        // Patient 2: Ibrahim TRAORE - SURVEILLANCE
        $p2 = Patient::create([
            'ipu' => Patient::generateIpu(),
            'name' => 'TRAORE',
            'first_name' => 'Ibrahim',
            'dob' => '1967-11-20',
            'gender' => 'Homme',
            'phone' => '+225 05 23 45 67 89',
            'email' => 'ibrahim.traore@test.ci',
            'password' => Hash::make('password'),
            'address' => 'Marcory',
            'city' => 'Abidjan',
            'blood_group' => 'A+',
            'is_active' => true
        ]);

        $a2 = Admission::create([
            'patient_id' => $p2->id,
            'room_id' => $room2->id,
            'bed_number' => 'A',
            'doctor_id' => $medecin->id,
            'admission_date' => now()->subDay(),
            'admission_type' => 'scheduled',
            'status' => 'active',
            'alert_level' => 'warning',
            'admission_reason' => 'Hypertension artérielle - Ajustement du traitement'
        ]);

        ClinicalObservation::create([
            'patient_id' => $p2->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '37.2',
            'unit' => '°C',
            'observation_datetime' => now(),
            'notes' => 'Température normale'
        ]);

        $this->command->info(" Patient SURVEILLANCE: Ibrahim TRAORE ({$p2->ipu})");

        // Patient 3: Marie KOFFI - STABLE
        $p3 = Patient::create([
            'ipu' => Patient::generateIpu(),
            'name' => 'KOFFI',
            'first_name' => 'Marie',
            'dob' => '1989-03-08',
            'gender' => 'Femme',
            'phone' => '+225 01 98 76 54 32',
            'email' => 'marie.koffi@test.ci',
            'password' => Hash::make('password'),
            'address' => 'Yopougon',
            'city' => 'Abidjan',
            'blood_group' => 'B+',
            'is_active' => true
        ]);

        $a3 = Admission::create([
            'patient_id' => $p3->id,
            'room_id' => $room3->id,
            'bed_number' => 'A',
            'doctor_id' => $medecin->id,
            'admission_date' => now()->subDays(3),
            'admission_type' => 'scheduled',
            'status' => 'active',
            'alert_level' => 'stable',
            'admission_reason' => 'Surveillance post-opératoire - Appendicectomie'
        ]);

        ClinicalObservation::create([
            'patient_id' => $p3->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '36.8',
            'unit' => '°C',
            'observation_datetime' => now(),
            'notes' => 'Évolution favorable'
        ]);

        $this->command->info(" Patient STABLE: Marie KOFFI ({$p3->ipu})");

        // Patient 4: Kouadio YAO - CRITIQUE
        $p4 = Patient::create([
            'ipu' => Patient::generateIpu(),
            'name' => 'YAO',
            'first_name' => 'Kouadio',
            'dob' => '1955-07-12',
            'gender' => 'Homme',
            'phone' => '+225 07 11 22 33 44',
            'email' => 'kouadio.yao@test.ci',
            'password' => Hash::make('password'),
            'address' => 'Adjamé',
            'city' => 'Abidjan',
            'blood_group' => 'AB+',
            'allergies' => json_encode(['Pollen']),
            'is_active' => true
        ]);

        $a4 = Admission::create([
            'patient_id' => $p4->id,
            'room_id' => $room3->id,
            'bed_number' => 'B',
            'doctor_id' => $medecin->id,
            'admission_date' => now(),
            'admission_type' => 'emergency',
            'status' => 'active',
            'alert_level' => 'critical',
            'admission_reason' => 'Pneumonie sévère - Détresse respiratoire'
        ]);

        ClinicalObservation::create([
            'patient_id' => $p4->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '39.8',
            'unit' => '°C',
            'observation_datetime' => now(),
            'is_critical' => true,
            'notes' => 'Fièvre + Toux productive + Dyspnée'
        ]);

        $this->command->info(" Patient CRITIQUE: Kouadio YAO ({$p4->ipu})");

        $this->command->info("\n 4 PATIENTS CRÉÉS AVEC SUCCÈS !");
        $this->command->info(" 2 Critiques  | 1 Surveillance  | 1 Stable ");
        $this->command->info(" http://127.0.0.1:8000/medecin/dashboard");
    }
}
