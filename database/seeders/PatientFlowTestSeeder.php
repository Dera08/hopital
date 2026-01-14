<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Appointment, Patient, User, Service};
use Carbon\Carbon;

class PatientFlowTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Récupérer les acteurs existants (Hôpital 2 / Dr. Kouamé)
        $hospitalId = 2; 
        $doctor = User::where('name', 'Dr. Kouamé Jean')->first();
        
        // On récupère la prestation "Consultation de Cardiologie"
        $prestation = Service::where('code', 'CONS-CARDIO')
                             ->where('hospital_id', $hospitalId)
                             ->first();

        if (!$doctor || !$prestation) {
            $this->command->error("Erreur : Assurez-vous que le DatabaseSeeder a bien créé le Dr. Kouamé et la prestation CONS-CARDIO.");
            return;
        }

        // 2. Création du nouveau patient de test
        $patient = Patient::updateOrCreate(
            ['ipu' => 'IPU-TEST-001'],
            [
                'hospital_id' => $hospitalId,
                'name' => 'Kouadio Konan',
                'first_name' => 'Konan',
                'dob' => '1990-01-01',
                'gender' => 'Homme',
                'is_active' => true,
            ]
        );

        // 3. Création du rendez-vous précisément pour AUJOURD'HUI
        // Utilisation de Carbon pour être sûr de la date système
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_datetime' => Carbon::now()->setHour(14)->setMinute(30),
            'service_id' => $doctor->service_id,
            'hospital_id' => $hospitalId,
            'status' => 'scheduled',
            'reason' => 'Test de flux : Etape 1 (Caisse)',
            'type' => 'consultation',
        ]);

        // Attacher la prestation au rendez-vous
        $appointment->prestations()->attach($prestation->id, [
            'quantity' => 1,
            'unit_price' => $prestation->price ?? 0,
            'total' => $prestation->price ?? 0,
            'added_at' => now(),
        ]);

        $this->command->info("✅ Succès : Kouadio Konan est prêt pour le test du 08/01/2026 !");
    }
}