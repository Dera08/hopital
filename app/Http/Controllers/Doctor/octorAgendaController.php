<?php

namespace App\Http\Controllers\Doctor; // Assurez-vous d'avoir ce répertoire
// ou gardez App\Http\Controllers si vous ne voulez pas de sous-répertoire

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AuditLog; // Utilisé pour les logs
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema; // Ajout pour la robustesse

class DoctorAgendaController extends Controller
{
    /**
     * Afficher la liste des rendez-vous (du jour ou liste complète)
     */
    public function index() // Anciennement todayAppointments
    {
        $doctor = Auth::user();
        $today = Carbon::today();

        // Récupérer tous les rendez-vous du jour
        $appointments = Appointment::where('doctor_id', $doctor->id)
            // Utilisation de la logique de détection de colonne pour la compatibilité
            ->whereDate($this->getDateColumnName(), $today)
            ->with(['patient', 'service'])
            ->orderBy($this->getTimeColumnName(), 'asc')
            ->get();

        // Grouper par statut pour les statistiques
        $stats = [
            'total' => $appointments->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            // ... autres statuts
        ];

        // Renvoyer vers une vue de liste/agenda pour les RDV du jour
        return view('doctor.appointments.today_list', compact('appointments', 'stats', 'today'));
    }

    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Vérifier que le rendez-vous appartient bien au médecin
        if ($appointment->doctor_id !== Auth::id()) {
             // ... (Logique d'erreur 403 inchangée)
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:scheduled,confirmed,cancelled,completed,no_show,pending'
        ]);

        $oldStatus = $appointment->status;
        $appointment->update(['status' => $validated['status']]);

        // Créer un log d'audit (assurez-vous que le modèle AuditLog est bien importé/utilisable)
        AuditLog::create([
             // ... (Logique de log inchangée)
            'user_id' => Auth::id(),
            'action' => 'update_appointment_status',
            'resource_type' => 'Appointment',
            'resource_id' => $appointment->id,
            'description' => "Statut modifié de '{$oldStatus}' à '{$validated['status']}'",
            'old_values' => json_encode(['status' => $oldStatus]),
            'new_values' => json_encode(['status' => $validated['status']]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
             // ... (Réponse JSON inchangée)
            'success' => true,
            'message' => 'Statut mis à jour avec succès.',
            'new_status' => $validated['status'],
            'appointment' => $appointment->load('patient')
        ]);
    }

    /**
     * Afficher les détails d'un rendez-vous
     */
    public function show(Appointment $appointment)
    {
        // Vérifier que le rendez-vous appartient bien au médecin
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $appointment->load(['patient', 'service']);

        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Ajouter des notes au rendez-vous
     */
    public function addNotes(Request $request, Appointment $appointment)
    {
        // Vérifier que le rendez-vous appartient bien au médecin
        if ($appointment->doctor_id !== Auth::id()) {
            return back()->with('error', 'Accès non autorisé');
        }

        $validated = $request->validate([
            'notes' => 'required|string|max:5000'
        ]);

        $appointment->update([
            'notes' => $validated['notes']
        ]);

        return back()->with('success', 'Notes ajoutées avec succès.');
    }

    /**
     * Vue calendrier des rendez-vous (Mensuel)
     */
    public function calendar(Request $request)
    {
        $doctor = Auth::user();
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        
        // La requête ici est redondante si on utilise l'API pour le FullCalendar,
        // mais elle peut servir pour le premier chargement.
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereYear($this->getDateColumnName(), $date->year)
            ->whereMonth($this->getDateColumnName(), $date->month)
            ->with(['patient'])
            ->get();

        return view('doctor.appointments.calendar', compact('appointments', 'date'));
    }

    /**
     * API pour obtenir les rendez-vous (pour le calendrier dynamique)
     */
    public function getAppointments(Request $request)
    {
        $doctor = Auth::user();
        
        // Assurez-vous que 'start' et 'end' sont fournis par le calendrier (FullCalendar ou autre)
        $start = $request->input('start');
        $end = $request->input('end');

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween($this->getDateColumnName(), [$start, $end])
            ->with(['patient'])
            ->get()
            ->map(function ($appointment) {
                // Assurez-vous que les colonnes 'appointment_datetime' ou 'date' + 'time' existent et sont utilisées
                $start_time = $appointment->appointment_datetime ?? $appointment->date;
                $end_time = Carbon::parse($start_time)->addMinutes($appointment->duration ?? 30); // Supposons 30 min par défaut

                return [
                    'id' => $appointment->id,
                    // Utiliser le nom complet du patient
                    'title' => ($appointment->patient->first_name ?? '') . ' ' . ($appointment->patient->name ?? 'RDV'), 
                    'start' => Carbon::parse($start_time)->format('Y-m-d H:i:s'),
                    'end' => $end_time->format('Y-m-d H:i:s'),
                    'status' => $appointment->status,
                    'color' => $this->getStatusColor($appointment->status),
                    'url' => route('doctor.appointments.show', $appointment->id), // Ajout d'une URL pour le clic
                ];
            });

        return response()->json($appointments);
    }
    
    // --- Méthodes utilitaires pour la compatibilité ---
    
    private function getDateColumnName()
    {
        $appointmentColumns = Schema::getColumnListing('appointments');
        if (in_array('appointment_datetime', $appointmentColumns)) {
            return 'appointment_datetime';
        } elseif (in_array('date', $appointmentColumns)) {
            return 'date'; // ou 'appointment_date' si vous l'avez
        }
        return 'created_at'; // Fallback
    }

    private function getTimeColumnName()
    {
        $appointmentColumns = Schema::getColumnListing('appointments');
        // Si 'appointment_datetime' est là, la date et l'heure sont dans la même colonne
        if (in_array('appointment_datetime', $appointmentColumns)) {
            return 'appointment_datetime';
        } elseif (in_array('appointment_time', $appointmentColumns)) {
            return 'appointment_time';
        } elseif (in_array('time', $appointmentColumns)) {
            return 'time';
        }
        return 'created_at'; // Fallback
    }

    /**
     * Obtenir la couleur selon le statut
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'scheduled' => '#3B82F6', // Bleu
            'confirmed' => '#10B981', // Vert
            'completed' => '#6B7280', // Gris
            'cancelled' => '#EF4444', // Rouge
            'no_show' => '#F59E0B', // Orange
            'pending' => '#FFC107', // Jaune
            default => '#6B7280',
        };
    }
}