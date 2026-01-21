<?php

namespace App\Http\Controllers;

use App\Models\PatientVital;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Admission; // Ajouté pour la clarté
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurseController extends Controller
{
    /**
     * Affiche le tableau de bord de l'infirmier
     */
    public function index()
    {
        $user = auth()->user();

        // 1. On récupère les RDV (Seulement si FACTURE PAYÉE)
        $appointments = Appointment::with(['patient', 'doctor', 'invoices'])
            ->where('hospital_id', $user->hospital_id)
            ->where('service_id', $user->service_id)
            ->where('status', 'paid')
            ->whereDate('appointment_datetime', now()->toDateString())
            ->get();

        // 2. Historique des dossiers envoyés (30 dernières minutes)
        $sentFiles = PatientVital::where('hospital_id', $user->hospital_id)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest()
            ->get();

        // 3. Mes patients hospitalisés
        $myPatients = Admission::with('patient')
            ->where('hospital_id', $user->hospital_id)
            ->where('status', 'active')
            ->get();

        return view('nurse.dashboard', [
            'sentFiles' => $sentFiles,
            'appointments' => $appointments,
            'myPatients' => $myPatients
        ]);
    }

    /**
     * Enregistre les constantes et envoie au médecin
     */
    public function store(Request $request)
    {
        try {
            // Vérification des doublons pour aujourd'hui
            $existingRecord = PatientVital::where('patient_ipu', $request->patient_ipu)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if ($existingRecord) {
                return $request->ajax() 
                    ? response()->json(['success' => false, 'message' => 'Déjà envoyé.'], 422)
                    : redirect()->back()->with('error', 'Déjà envoyé.');
            }

            // 1. Enregistrement des constantes vitales
            PatientVital::create([
                'patient_name'   => $request->patient_name,
                'patient_ipu'    => $request->patient_ipu,
                'urgency'        => $request->urgency,
                'reason'         => $request->reason,
                'temperature'    => $request->temperature,
                'pulse'          => $request->pulse,
                'blood_pressure' => $request->blood_pressure ?? '12/8',
                'user_id'        => auth()->id(),
                'hospital_id'    => auth()->user()->hospital_id,
                'service_id'     => auth()->user()->service_id,
                'status'         => 'active',
            ]);

            // 2. MISE À JOUR DU STATUT DU RDV (Le patient sort de la liste infirmier)
            $appointment = Appointment::whereHas('patient', function($q) use ($request) {
                    $q->where('ipu', $request->patient_ipu);
                })
                ->where('hospital_id', auth()->user()->hospital_id) 
                ->whereDate('appointment_datetime', now()->toDateString())
                ->where('status', 'scheduled') 
                ->first();

            if ($appointment) {
                $appointment->update(['status' => 'prepared']); 
            }

            return $request->ajax() 
                ? response()->json(['success' => true])
                : redirect()->back();

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprime un dossier envoyé par erreur
     */
    public function destroy($id)
    {
        try {
            $vital = PatientVital::findOrFail($id);
            if ($vital->user_id !== auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
            }
            $vital->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}