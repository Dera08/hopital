<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\User;
use App\Models\Room;
use App\Models\Service;
use App\Models\ClinicalObservation;

class PatientsTestSeeder extends Seeder
{
    public function run(): void
    {
        $medecin = User::where('role', 'doctor')->first();
        
        if (!$medecin) {
            $this->command->error('❌ Aucun médecin trouvé');
            return;
        }
        
        $this->command->info("✅ Médecin: Dr. {$medecin->name}");
        
        $service = Service::firstOrCreate(
            ['code' => 'MED_GEN'],
            ['name' => 'Médecine Générale', 'is_active' => true]
        );
        
        $room1 = Room::firstOrCreate(['room_number' => '101'], [
            'bed_capacity' => 2,
            'service_id' => $service->id,
            'status' => 'occupied',
            'type' => 'standard',
            'is_active' => true
        ]);
        
        $room2 = Room::firstOrCreate(['room_number' => '102'], [
            'bed_capacity' => 1,
            'service_id' => $service->id,
            'status' => 'occupied',
            'type' => 'standard',
            'is_active' => true
        ]);
        
        $room3 = Room::firstOrCreate(['room_number' => '103'], [
            'bed_capacity' => 2,
            'service_id' => $service->id,
            'status' => 'occupied',
            'type' => 'standard',
            'is_active' => true
        ]);
        
        // Patient 1 - CRITIQUE
        $p1 = Patient::firstOrCreate(
            ['email' => 'aminata.kouassi@test.ci'],
            [
                'ipu' => Patient::generateIpu(),
                'name' => 'KOUASSI',
                'first_name' => 'Aminata',
                'dob' => '1994-05-15',
                'gender' => 'Femme',
                'phone' => '+225 07 12 34 56 78',
                'password' => bcrypt('password'),
                'address' => 'Cocody',
                'city' => 'Abidjan',
                'blood_group' => 'O+',
                'allergies' => json_encode(['Pénicilline', 'Aspirine']),
                'is_active' => true,
            ]
        );
        
        Admission::create([
            'patient_id' => $p1->id,
            'room_id' => $room1->id,
            'bed_number' => 'A',
            'doctor_id' => $medecin->id,
            'admission_date' => now()->subDays(2),
            'admission_type' => 'emergency',
            'status' => 'active',
            'alert_level' => 'critical',
            'admission_reason' => 'Suspicion de Paludisme - Fièvre élevée persistante',
        ]);
        
        ClinicalObservation::create([
            'patient_id' => $p1->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '39.5',
            'unit' => '°C',
            'observation_datetime' => now(),
            'is_critical' => true,
            'notes' => 'Fièvre élevée depuis 48h',
        ]);
        
        $this->command->info("✅ Patient CRITIQUE: Aminata KOUASSI ({$p1->ipu})");
        
        // Patient 2 - SURVEILLANCE
        $p2 = Patient::firstOrCreate(
            ['email' => 'ibrahim.traore@test.ci'],
            [
                'ipu' => Patient::generateIpu(),
                'name' => 'TRAORE',
                'first_name' => 'Ibrahim',
                'dob' => '1967-11-20',
                'gender' => 'Homme',
                'phone' => '+225 05 23 45 67 89',
                'password' => bcrypt('password'),
                'address' => 'Marcory',
                'city' => 'Abidjan',
                'blood_group' => 'A+',
                'is_active' => true,
            ]
        );
        
        Admission::create([
            'patient_id' => $p2->id,
            'room_id' => $room2->id,
            'bed_number' => 'A',
            'doctor_id' => $medecin->id,
            'admission_date' => now()->subDay(),
            'admission_type' => 'scheduled',
            'status' => 'active',
            'alert_level' => 'warning',
            'admission_reason' => 'Hypertension artérielle - Ajustement',
        ]);
        
        ClinicalObservation::create([
            'patient_id' => $p2->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '37.2',
            'unit' => '°C',
            'observation_datetime' => now(),
            'notes' => 'Température normale',
        ]);
        
        $this->command->info("✅ Patient SURVEILLANCE: Ibrahim TRAORE ({$p2->ipu})");

        // Patient 3 - STABLE
        $p3 = Patient::firstOrCreate(
            ['email' => 'marie.koffi@test.ci'],
            [
                'ipu' => Patient::generateIpu(),
                'name' => 'KOFFI',
                'first_name' => 'Marie',
                'dob' => '1989-03-08',
                'gender' => 'Femme',
                'phone' => '+225 01 98 76 54 32',
                'password' => bcrypt('password'),
                'address' => 'Yopougon',
                'city' => 'Abidjan',
                'blood_group' => 'B+',
                'is_active' => true,
            ]
        );

        Admission::create([
            'patient_id' => $p3->id,
            'room_id' => $room3->id,
            'bed_number' => 'A',
            'doctor_id' => $medecin->id,
            'admission_date' => now()->subDays(3),
            'admission_type' => 'scheduled',
            'status' => 'active',
            'alert_level' => 'stable',
            'admission_reason' => 'Surveillance post-opératoire - Appendicectomie',
        ]);

        ClinicalObservation::create([
            'patient_id' => $p3->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '36.8',
            'unit' => '°C',
            'observation_datetime' => now(),
            'notes' => 'Évolution favorable',
        ]);

        $this->command->info("✅ Patient STABLE: Marie KOFFI ({$p3->ipu})");

        // Patient 4 - CRITIQUE
        $p4 = Patient::firstOrCreate(
            ['email' => 'kouadio.yao@test.ci'],
            [
                'ipu' => 'PAT202506083',
                'name' => 'KOUADIO',
                'first_name' => 'YK YAO',
                'dob' => '1955-01-01',
                'gender' => 'M',
                'phone' => '+225 07 11 22 33 44',
                'password' => bcrypt('password'),
                'address' => 'Adjamé',
                'city' => 'Abidjan',
                'blood_group' => 'AB+',
                'allergies' => json_encode(['Pollen']),
                'is_active' => true,
            ]
        );

        Admission::create([
            'patient_id' => $p4->id,
            'room_id' => $room3->id,
            'bed_number' => 'B',
            'doctor_id' => $medecin->id,
            'admission_date' => now(),
            'admission_type' => 'emergency',
            'status' => 'active',
            'alert_level' => 'critical',
            'admission_reason' => 'Pneumonie sévère - Détresse respiratoire',
        ]);

        ClinicalObservation::create([
            'patient_id' => $p4->id,
            'user_id' => $medecin->id,
            'type' => 'temperature',
            'value' => '39.8',
            'unit' => '°C',
            'observation_datetime' => now(),
            'is_critical' => true,
            'notes' => 'Fièvre + Toux productive + Dyspnée',
        ]);

        $this->command->info("✅ Patient CRITIQUE: Kouadio YAO ({$p4->ipu})");

        $this->command->info("\n🎉 4 PATIENTS CRÉÉS AVEC SUCCÈS !");
        $this->command->info("🌐 http://127.0.0.1:8000/medecin/dashboard");
        
    }
}