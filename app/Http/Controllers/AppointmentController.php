<?php

namespace App\Http\Controllers;

use App\Models\{Appointment, Patient, User, Service, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Notification};
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Appointment::with(['patient', 'doctor', 'service']);

        // Filtrer selon le rôle
        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        } elseif (!$user->isAdmin() && $user->service_id) {
            $query->where('service_id', $user->service_id);
        }

        // Filtres de recherche
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_datetime', $request->date);
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Vue par défaut : prochains rendez-vous
        if (!$request->filled('date') && !$request->filled('status')) {
            $query->where('appointment_datetime', '>=', now())
                  ->orderBy('appointment_datetime');
        } else {
            $query->latest('appointment_datetime');
        }

        $appointments = $query->paginate(20);

        // Données pour les filtres
        $doctors = User::where('role', 'doctor')
                      ->where('is_active', true)
                      ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
                          return $q->where('service_id', $user->service_id);
                      })
                      ->get();

        return view('appointments.index', compact('appointments', 'doctors'));
    }

    public function create(Request $request)
    {
        $patientId = $request->input('patient_id');
        $patient = $patientId ? Patient::findOrFail($patientId) : null;

        $user = auth()->user();
        
        // Liste des médecins disponibles
        $doctors = User::where('role', 'doctor')
                      ->where('is_active', true)
                      ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
                          return $q->where('service_id', $user->service_id);
                      })
                      ->with('service')
                      ->get();

        $services = Service::where('is_active', true)->get();

        return view('appointments.create', compact('patient', 'doctors', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_datetime' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:240',
            'type' => 'required|in:consultation,follow_up,emergency',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|string|max:255',
        ]);

        // Vérifier la disponibilité du médecin
        $conflicts = $this->checkDoctorAvailability(
            $validated['doctor_id'],
            $validated['appointment_datetime'],
            $validated['duration']
        );

        if ($conflicts > 0) {
            return back()->withInput()->withErrors([
                'appointment_datetime' => 'Le médecin n\'est pas disponible à cette date/heure.'
            ]);
        }

        DB::beginTransaction();
        try {
            $appointment = Appointment::create($validated);

            // Journalisation
            AuditLog::log('create', 'Appointment', $appointment->id, [
                'description' => 'Création d\'un rendez-vous',
                'new' => $appointment->toArray()
            ]);

            // Si récurrent, créer les rendez-vous futurs
            if ($validated['is_recurring'] && $validated['recurrence_pattern']) {
                $this->createRecurringAppointments($appointment);
            }

            DB::commit();

            return redirect()->route('appointments.index')
                           ->with('success', 'Rendez-vous créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du rendez-vous.']);
        }
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'service']);

        // Vérifier les permissions
        if (!$this->canAccessAppointment($appointment)) {
            abort(403, 'Accès non autorisé.');
        }

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        if (!$this->canAccessAppointment($appointment)) {
            abort(403, 'Accès non autorisé.');
        }

        $user = auth()->user();
        
        $doctors = User::where('role', 'doctor')
                      ->where('is_active', true)
                      ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
                          return $q->where('service_id', $user->service_id);
                      })
                      ->get();

        $services = Service::where('is_active', true)->get();

        return view('appointments.edit', compact('appointment', 'doctors', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (!$this->canAccessAppointment($appointment)) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_datetime' => 'required|date',
            'duration' => 'required|integer|min:15|max:240',
            'status' => 'required|in:scheduled,confirmed,cancelled,completed,no_show',
            'type' => 'required|in:consultation,follow_up,emergency',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Vérifier disponibilité si changement de date/médecin
        if ($appointment->doctor_id != $validated['doctor_id'] || 
            $appointment->appointment_datetime != $validated['appointment_datetime']) {
            
            $conflicts = $this->checkDoctorAvailability(
                $validated['doctor_id'],
                $validated['appointment_datetime'],
                $validated['duration'],
                $appointment->id
            );

            if ($conflicts > 0) {
                return back()->withInput()->withErrors([
                    'appointment_datetime' => 'Le médecin n\'est pas disponible à cette date/heure.'
                ]);
            }
        }

        DB::beginTransaction();
        try {
            $oldData = $appointment->toArray();
            
            $appointment->update($validated);

            AuditLog::log('update', 'Appointment', $appointment->id, [
                'description' => 'Modification d\'un rendez-vous',
                'old' => $oldData,
                'new' => $appointment->toArray()
            ]);

            DB::commit();

            return redirect()->route('appointments.show', $appointment)
                           ->with('success', 'Rendez-vous mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(Appointment $appointment)
    {
        if (!$this->canAccessAppointment($appointment)) {
            abort(403, 'Accès non autorisé.');
        }

        DB::beginTransaction();
        try {
            AuditLog::log('delete', 'Appointment', $appointment->id, [
                'description' => 'Suppression d\'un rendez-vous',
                'old' => $appointment->toArray()
            ]);

            $appointment->delete();

            DB::commit();

            return redirect()->route('appointments.index')
                           ->with('success', 'Rendez-vous supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    public function confirm(Appointment $appointment)
    {
        if (!$this->canAccessAppointment($appointment)) {
            abort(403, 'Accès non autorisé.');
        }

        $appointment->update(['status' => 'confirmed']);

        AuditLog::log('update', 'Appointment', $appointment->id, [
            'description' => 'Confirmation du rendez-vous'
        ]);

        return back()->with('success', 'Rendez-vous confirmé.');
    }

    public function cancel(Appointment $appointment)
    {
        if (!$this->canAccessAppointment($appointment)) {
            abort(403, 'Accès non autorisé.');
        }

        $appointment->update(['status' => 'cancelled']);

        AuditLog::log('update', 'Appointment', $appointment->id, [
            'description' => 'Annulation du rendez-vous'
        ]);

        return back()->with('success', 'Rendez-vous annulé.');
    }

    public function doctorAvailability(Request $request, User $doctor)
    {
        // API pour récupérer les disponibilités d'un médecin
        $date = $request->input('date', now()->format('Y-m-d'));
        
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_datetime', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['appointment_datetime', 'duration']);

        // Récupérer les disponibilités configurées
        $availability = $doctor->availability()
            ->where('day_of_week', strtolower(Carbon::parse($date)->englishDayOfWeek))
            ->where('is_active', true)
            ->first();

        if (!$availability) {
            return response()->json([
                'available' => false,
                'message' => 'Le médecin n\'est pas disponible ce jour.'
            ]);
        }

        // Vérifier les congés
        $onLeave = $doctor->leaves()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();

        if ($onLeave) {
            return response()->json([
                'available' => false,
                'message' => 'Le médecin est en congé.'
            ]);
        }

        // Générer les créneaux disponibles
        $slots = $this->generateTimeSlots(
            $availability->start_time,
            $availability->end_time,
            $availability->slot_duration,
            $appointments
        );

        return response()->json([
            'available' => true,
            'slots' => $slots
        ]);
    }

    private function checkDoctorAvailability($doctorId, $datetime, $duration, $excludeId = null)
    {
        $startTime = Carbon::parse($datetime);
        $endTime = $startTime->copy()->addMinutes($duration);

        return Appointment::where('doctor_id', $doctorId)
            ->where('status', '!=', 'cancelled')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('appointment_datetime', [$startTime, $endTime])
                      ->orWhere(function($q) use ($startTime, $endTime) {
                          $q->where('appointment_datetime', '<', $startTime)
                            ->whereRaw('DATE_ADD(appointment_datetime, INTERVAL duration MINUTE) > ?', [$startTime]);
                      });
            })
            ->count();
    }

    private function generateTimeSlots($startTime, $endTime, $duration, $existingAppointments)
    {
        $slots = [];
        $current = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        while ($current->addMinutes($duration) <= $end) {
            $slotStart = $current->copy();
            $slotEnd = $slotStart->copy()->addMinutes($duration);

            // Vérifier si le créneau est libre
            $isOccupied = false;
            foreach ($existingAppointments as $appointment) {
                $aptStart = Carbon::parse($appointment->appointment_datetime);
                $aptEnd = $aptStart->copy()->addMinutes($appointment->duration);

                if ($slotStart < $aptEnd && $slotEnd > $aptStart) {
                    $isOccupied = true;
                    break;
                }
            }

            if (!$isOccupied) {
                $slots[] = [
                    'time' => $slotStart->format('H:i'),
                    'available' => true
                ];
            }
        }

        return $slots;
    }

    private function createRecurringAppointments(Appointment $baseAppointment)
    {
        // Logique pour créer des rendez-vous récurrents
        // À implémenter selon les besoins (hebdomadaire, mensuel, etc.)
    }

    private function canAccessAppointment(Appointment $appointment): bool
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor() && $appointment->doctor_id === $user->id) {
            return true;
        }

        if ($user->service_id && $appointment->service_id === $user->service_id) {
            return true;
        }

        return false;
    }
}