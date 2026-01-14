<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Patient, Appointment, User, Service, Prestation, Invoice}; // Ajout de Invoice ici

class NouveauRendezVousSeeder extends Seeder
{
    public function run(): void
    {
        // 1. On trouve le médecin (Dr. Kouamé Jean)
        $medecin = User::where('name', 'Dr. Kouamé Jean')->first();

        if (!$medecin) {
            $this->command->error("Erreur: Le Dr. Kouamé n'existe pas.");
            return;
        }

        // 2. On crée le patient
        $patient = Patient::create([
            'hospital_id' => $medecin->hospital_id,
            'ipu' => 'IPU-' . rand(1000, 9999),
            'name' => 'Kouadio',
            'first_name' => 'Konan Test',
            'gender' => 'Homme',
            'dob' => '1995-01-01',
            'phone' => '+225 00 00 00 00 00',
            'is_active' => true,
        ]);

        // 3. On récupère la PRESTATION
        $prestation = Prestation::where('code', 'CONS-CARDIO')
                                ->where('hospital_id', $medecin->hospital_id)
                                ->first();

        if (!$prestation) {
            $this->command->error("Erreur: La prestation CONS-CARDIO n'existe pas.");
            return;
        }

        // 4. On crée le rendez-vous
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $medecin->id,
            'hospital_id' => $medecin->hospital_id,
            'service_id' => $medecin->service_id,
            'appointment_datetime' => now(),
            'status' => 'scheduled',
            'type' => 'consultation',
            'reason' => 'Test de flux REEL : Patient payé'
        ]);

        // 5. On attache la prestation au rendez-vous
        $appointment->prestations()->attach($prestation->id, [
            'quantity' => 1,
            'unit_price' => $prestation->price,
            'total' => $prestation->price,
            'added_at' => now(),
        ]);

        // 6. CRÉATION DE LA FACTURE RÉELLE (Payée)
        // Maintenant que le patient a payé, l'infirmier doit le voir
        Invoice::create([
            'hospital_id' => $medecin->hospital_id,
            'invoice_number' => 'INV-' . date('ymd') . '-' . rand(1000, 9999),
            'patient_id' => $patient->id,
            'appointment_id' => $appointment->id,
            'total' => $prestation->price,
            'subtotal' => $prestation->price,
            'tax' => 0,
            'status' => 'paid', // <--- Maintenant payé, l'infirmier voit le patient
            'invoice_date' => now(),
            'paid_at' => now(),      // Payé maintenant
            'payment_method' => 'cash' // Mode de paiement
        ]);

        $this->command->info("✅ Succès ! Patient créé et payé. Statut : PAYÉ.");
    }
}