<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Hash, DB};
use App\Models\{Service, User, Patient, Room};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Nettoyer les tables
        Service::truncate();
        User::truncate();
        Patient::truncate();
        Room::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Créer les services
        $this->seedServices();

        // Créer les utilisateurs
        $this->seedUsers();

        // Créer des patients de test
        $this->seedPatients();

        // Créer des chambres
        $this->seedRooms();

        $this->command->info('✅ Base de données initialisée avec succès!');
    }

    private function seedServices(): void
    {
        $services = [
            ['name' => 'Urgences', 'code' => 'URG', 'description' => 'Service des urgences', 'is_active' => true],
            ['name' => 'Cardiologie', 'code' => 'CARD', 'description' => 'Service de cardiologie', 'is_active' => true],
            ['name' => 'Pédiatrie', 'code' => 'PED', 'description' => 'Service de pédiatrie', 'is_active' => true],
            ['name' => 'Chirurgie', 'code' => 'CHIR', 'description' => 'Service de chirurgie générale', 'is_active' => true],
            ['name' => 'Maternité', 'code' => 'MAT', 'description' => 'Service de maternité', 'is_active' => true],
            ['name' => 'Radiologie', 'code' => 'RAD', 'description' => 'Service de radiologie', 'is_active' => true],
            ['name' => 'Oncologie', 'code' => 'ONC', 'description' => 'Service d\'oncologie', 'is_active' => true],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('✓ Services créés');
    }

    private function seedUsers(): void
    {
        // Admin Principal
        User::create([
            'name' => 'Admin Système',
            'email' => 'admin@hospisis.ci',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'service_id' => null,
            'is_active' => true,
            'mfa_enabled' => false,
        ]);

        // Responsable Administratif
        User::create([
            'name' => 'Sophie Martin',
            'email' => 'admin.responsable@hospisis.ci',
            'password' => Hash::make('password'),
            'role' => 'administrative',
            'service_id' => null,
            'phone' => '+225 07 00 00 01',
            'is_active' => true,
            'mfa_enabled' => false,
        ]);

        // Médecins par service
        $doctors = [
            ['name' => 'Dr. Jean Kouassi', 'email' => 'dr.kouassi@hospisis.ci', 'service' => 'Cardiologie', 'registration' => 'MED2024001'],
            ['name' => 'Dr. Marie Bamba', 'email' => 'dr.bamba@hospisis.ci', 'service' => 'Pédiatrie', 'registration' => 'MED2024002'],
            ['name' => 'Dr. Paul N\'Guessan', 'email' => 'dr.nguessan@hospisis.ci', 'service' => 'Chirurgie', 'registration' => 'MED2024003'],
            ['name' => 'Dr. Fatou Diallo', 'email' => 'dr.diallo@hospisis.ci', 'service' => 'Maternité', 'registration' => 'MED2024004'],
            ['name' => 'Dr. Amadou Traoré', 'email' => 'dr.traore@hospisis.ci', 'service' => 'Urgences', 'registration' => 'MED2024005'],
        ];

        foreach ($doctors as $doctor) {
            User::create([
                'name' => $doctor['name'],
                'email' => $doctor['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'service_id' => Service::where('name', $doctor['service'])->first()->id,
                'registration_number' => $doctor['registration'],
                'phone' => '+225 07 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'is_active' => true,
                'mfa_enabled' => false,
            ]);
        }

        // Infirmiers
        $nurses = [
            ['name' => 'Infirmière Aya Touré', 'email' => 'inf.toure@hospisis.ci', 'service' => 'Cardiologie'],
            ['name' => 'Infirmier Marc Koné', 'email' => 'inf.kone@hospisis.ci', 'service' => 'Pédiatrie'],
            ['name' => 'Infirmière Aïcha Ouattara', 'email' => 'inf.ouattara@hospisis.ci', 'service' => 'Chirurgie'],
            ['name' => 'Infirmière Emma Yao', 'email' => 'inf.yao@hospisis.ci', 'service' => 'Maternité'],
            ['name' => 'Infirmier David Sanogo', 'email' => 'inf.sanogo@hospisis.ci', 'service' => 'Urgences'],
        ];

        foreach ($nurses as $nurse) {
            User::create([
                'name' => $nurse['name'],
                'email' => $nurse['email'],
                'password' => Hash::make('password'),
                'role' => 'nurse',
                'service_id' => Service::where('name', $nurse['service'])->first()->id,
                'phone' => '+225 05 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'is_active' => true,
                'mfa_enabled' => false,
            ]);
        }

        $this->command->info('✓ Utilisateurs créés (mot de passe par défaut: password)');
    }

    private function seedPatients(): void
    {
        $firstNames = ['Kofi', 'Ama', 'Koffi', 'Adjoua', 'Kouadio', 'N\'Goran', 'Yasmine', 'Ibrahim', 'Mariam', 'Seydou'];
        $lastNames = ['Kouassi', 'Yao', 'Koné', 'Traoré', 'Bamba', 'Ouattara', 'N\'Guessan', 'Diabaté', 'Sanogo', 'Touré'];
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $allergies = [
            ['Pénicilline'],
            ['Aspirine', 'Ibuprofène'],
            ['Arachides'],
            ['Latex'],
            [],
        ];

        for ($i = 0; $i < 50; $i++) {
            Patient::create([
                'ipu' => Patient::generateIpu(),
                'first_name' => $firstNames[array_rand($firstNames)],
                'name' => $lastNames[array_rand($lastNames)],
                'dob' => now()->subYears(rand(1, 80))->subDays(rand(0, 365)),
                'gender' => ['M', 'F'][rand(0, 1)],
                'address' => 'Abidjan, ' . ['Cocody', 'Yopougon', 'Treichville', 'Adjamé', 'Plateau'][rand(0, 4)],
                'city' => 'Abidjan',
                'postal_code' => sprintf('%02d BP %d', rand(1, 30), rand(100, 999)),
                'phone' => '+225 ' . ['07', '05', '01'][rand(0, 2)] . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'email' => $i < 20 ? 'patient' . ($i + 1) . '@email.ci' : null,
                'emergency_contact_name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'emergency_contact_phone' => '+225 07 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'blood_group' => $bloodGroups[array_rand($bloodGroups)],
                'allergies' => $allergies[array_rand($allergies)],
                'medical_history' => rand(0, 1) ? 'Antécédents: ' . ['Hypertension', 'Diabète', 'Asthme', 'Aucun'][rand(0, 3)] : null,
                'is_active' => true,
            ]);
        }

        $this->command->info('✓ 50 patients de test créés');
    }

    private function seedRooms(): void
    {
        $services = Service::all();

        foreach ($services as $service) {
            // Créer des chambres pour chaque service
            $roomCount = rand(5, 15);

            for ($i = 1; $i <= $roomCount; $i++) {
                Room::create([
                    'room_number' => $service->code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'bed_capacity' => rand(1, 4),
                    'service_id' => $service->id,
                    'status' => ['available', 'available', 'available', 'occupied', 'cleaning'][rand(0, 4)],
                    'type' => ['standard', 'standard', 'VIP', 'isolation'][rand(0, 3)],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✓ Chambres créées pour tous les services');
    }
}

/**
 * Seeder pour les disponibilités des médecins
 */
class DoctorAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->get();

        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        foreach ($doctors as $doctor) {
            foreach ($daysOfWeek as $day) {
                \App\Models\DoctorAvailability::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00',
                    'end_time' => '17:00',
                    'slot_duration' => 30,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✓ Disponibilités des médecins configurées');
    }
} 