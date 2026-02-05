<?php

namespace App\Http\Controllers;

use App\Models\{Admission, Patient, Room, User, Service, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::with(['patient', 'bed','room.service', 'doctor']);

        // Filtrer par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, afficher les admissions actives
            $query->where('status', 'active');
        }
          
        // Filtrer par service
        $user = auth()->user();
        if (!$user->isAdmin() && $user->service_id) {
            $query->whereHas('room', function($q) use ($user) {
                $q->where('service_id', $user->service_id);
            });
        }

        // Filtrer par dates
        if ($request->filled('date_from')) {
            $query->whereDate('admission_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('admission_date', '<=', $request->date_to);
        }

        $admissions = $query->latest('admission_date')->paginate(20);

        // Statistiques
        $stats = [
            'active' => Admission::where('status', 'active')->count(),
            'today' => Admission::whereDate('admission_date', today())->count(),
            'pending_discharge' => Admission::where('status', 'active')
                ->whereNotNull('discharge_date')
                ->whereDate('discharge_date', '<=', today())
                ->count(),
        ];
        // On ajoute 'bed' dans le "with"
      

        return view('admissions.index', compact('admissions', 'stats'));
    }

   public function create(Request $request)
{
    $patientId = $request->input('patient_id');
    $patient = $patientId ? Patient::findOrFail($patientId) : null;
    $user = auth()->user();

    // Chambres disponibles filtrées par service
    $rooms = Room::where('status', 'available')
        ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
            return $q->where('service_id', $user->service_id);
        })
        ->get();

    // LITS DISPONIBLES filtrés par service
    $availableBeds = \App\Models\Bed::where('is_available', true)
        ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
            // Filtrer les lits dont la chambre appartient au service de l'utilisateur
            return $q->whereHas('room', function($rq) use ($user) {
                $rq->where('service_id', $user->service_id);
            });
        })
        ->with('room.service') // Charger la relation pour affichage
        ->get();

    $doctors = User::where('role', 'doctor')
        ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
            return $q->where('service_id', $user->service_id);
        })
        ->get();

    // Ajoutez 'availableBeds' au compact
    return view('admissions.create', compact('patient', 'rooms', 'doctors', 'availableBeds'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'room_id' => 'required|exists:rooms,id',
        'bed_id' => 'required|exists:beds,id', // AJOUT : Lit obligatoire
        'doctor_id' => 'required|exists:users,id',
        'admission_date' => 'required|date',
        'admission_type' => 'required|in:emergency,scheduled,transfer',
        'admission_reason' => 'required|string|max:1000',
    ]);

    DB::beginTransaction();
    try {
        $room = Room::findOrFail($validated['room_id']);
        $bed = \App\Models\Bed::findOrFail($validated['bed_id']);

        // Créer l'admission (vérifiez que bed_id est dans le $fillable de Admission)
        $admission = Admission::create($validated);

        // --- ÉTAPE CRUCIALE ---
        // 1. On occupe le lit
        $bed->update(['is_available' => false]);
        
        // 2. On occupe la chambre
        $room->update(['status' => 'occupied']);

        DB::commit();
        return redirect()->route('admissions.show', $admission)->with('success', 'Patient admis au lit ' . $bed->bed_number);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => 'Erreur lors de l\'admission.']);
    }
}

    public function show(Admission $admission)
    {
        $admission->load(['patient', 'room.service', 'doctor']);

        // Vérifier les permissions
        if (!$this->canAccessAdmission($admission)) {
            abort(403, 'Accès non autorisé.');
        }

        // Récupérer les documents et notes liés
        $medicalRecords = $admission->patient->medicalRecords()
            ->where('created_at', '>=', $admission->admission_date)
            ->latest()
            ->get();

        $prescriptions = $admission->patient->prescriptions()
            ->where('created_at', '>=', $admission->admission_date)
            ->latest()
            ->get();

        return view('admissions.show', compact('admission', 'medicalRecords', 'prescriptions'));
    }

    public function edit(Admission $admission)
    {
        if (!$this->canAccessAdmission($admission)) {
            abort(403, 'Accès non autorisé.');
        }

        $user = auth()->user();

        $rooms = Room::where('is_active', true)
            ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
                return $q->where('service_id', $user->service_id);
            })
            ->with('service')
            ->get();

        $doctors = User::where('role', 'doctor')
            ->where('is_active', true)
            ->when(!$user->isAdmin() && $user->service_id, function($q) use ($user) {
                return $q->where('service_id', $user->service_id);
            })
            ->get();

        return view('admissions.edit', compact('admission', 'rooms', 'doctors'));
    }

    public function update(Request $request, Admission $admission)
    {
        if (!$this->canAccessAdmission($admission)) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'doctor_id' => 'required|exists:users,id',
            'admission_date' => 'required|date',
            'admission_type' => 'required|in:emergency,scheduled,transfer',
            'admission_reason' => 'required|string|max:1000',
            'discharge_date' => 'nullable|date|after:admission_date',
            'discharge_summary' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldData = $admission->toArray();
            $oldRoomId = $admission->room_id;

            $admission->update($validated);

            // Si changement de chambre
            if ($oldRoomId != $validated['room_id']) {
                // Libérer l'ancienne chambre
                if ($oldRoomId) {
                    Room::find($oldRoomId)->update(['status' => 'cleaning']);
                }
                // Occuper la nouvelle chambre
                Room::find($validated['room_id'])->update(['status' => 'occupied']);
            }

            AuditLog::log('update', 'Admission', $admission->id, [
                'description' => 'Modification de l\'admission',
                'old' => $oldData,
                'new' => $admission->toArray()
            ]);

            DB::commit();

            return redirect()->route('admissions.show', $admission)
                           ->with('success', 'Admission mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function discharge(Request $request, Admission $admission)
    {
        if (!$this->canAccessAdmission($admission)) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'discharge_date' => 'required|date|after_or_equal:' . $admission->admission_date->format('Y-m-d'),
            'discharge_summary' => 'required|string|min:50',
        ]);

        DB::beginTransaction();
        try {
            $admission->update([
                'status' => 'discharged',
                'discharge_date' => $request->discharge_date,
                'discharge_summary' => $request->discharge_summary,
            ]);

            // Libérer la chambre
            if ($admission->room) {
                $admission->room->update(['status' => 'cleaning']);
            }

            AuditLog::log('update', 'Admission', $admission->id, [
                'description' => 'Sortie du patient',
            ]);

            DB::commit();

            return redirect()->route('admissions.index')
                           ->with('success', 'Patient sorti avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la sortie.']);
        }
    }

    public function transfer(Request $request, Admission $admission)
    {
        if (!$this->canAccessAdmission($admission)) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'new_room_id' => 'required|exists:rooms,id|different:' . $admission->room_id,
            'transfer_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $newRoom = Room::findOrFail($request->new_room_id);

            if ($newRoom->status !== 'available') {
                return back()->withErrors(['new_room_id' => 'Cette chambre n\'est pas disponible.']);
            }

            $oldRoom = $admission->room;

            // Transférer
            $admission->update([
                'room_id' => $request->new_room_id,
                'status' => 'transferred',
            ]);

            // Créer une nouvelle admission active
            $newAdmission = $admission->replicate();
            $newAdmission->admission_date = now();
            $newAdmission->admission_type = 'transfer';
            $newAdmission->admission_reason = $request->transfer_reason;
            $newAdmission->status = 'active';
            $newAdmission->save();

            // Mettre à jour les chambres
            if ($oldRoom) {
                $oldRoom->update(['status' => 'cleaning']);
            }
            $newRoom->update(['status' => 'occupied']);

            AuditLog::log('create', 'Admission', $newAdmission->id, [
                'description' => 'Transfert patient vers ' . $newRoom->room_number,
            ]);

            DB::commit();

            return redirect()->route('admissions.show', $newAdmission)
                           ->with('success', 'Patient transféré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors du transfert.']);
        }
    }

    private function canAccessAdmission(Admission $admission): bool
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor() && $admission->doctor_id === $user->id) {
            return true;
        }

        if ($user->service_id && $admission->room && $admission->room->service_id === $user->service_id) {
            return true;
        }

        return false;
    }
}