<?php

namespace App\Http\Controllers;

use App\Models\{Room, Service, Bed};
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function bedManagement()
    {
        // On récupère les chambres avec leurs services et l'état de leurs lits
        $rooms = Room::with(['service', 'beds'])->orderBy('service_id')->get();
        return view('rooms.bed-management', compact('rooms'));
    }

    public function create()
    {
        $services = Service::where('hospital_id', auth()->user()->hospital_id)->get(); // Pour le menu déroulant
        return view('rooms.create', compact('services'));
    }

    public function store(Request $request)
{
    // 1. Validation des données saisies par l'Admin
    $request->validate([
        'room_number' => 'required|string|max:10',
        'service_id'  => 'required|exists:services,id',
        'capacity'    => 'required|integer|min:1|max:10', // Limite à 10 lits max par chambre par ex.
    ]);

    // 2. Création de la chambre
    $room = Room::create([
        'room_number'  => $request->room_number,
        'service_id'   => $request->service_id,
        'bed_capacity' => $request->capacity,
        'hospital_id'  => auth()->user()->hospital_id,
    ]);

    // 3. Création automatique des lits (La boucle magique)
    for ($i = 1; $i <= $request->capacity; $i++) {
        Bed::create([
            'room_id'      => $room->id,
            'bed_number'   => $request->room_number . "-L" . $i,
            'is_available' => true,
            'hospital_id'  => auth()->user()->hospital_id,
        ]);
    }

    return redirect()->route('rooms.bed-management')
                     ->with('success', "La chambre {$room->room_number} et ses {$request->capacity} lits ont été créés.");
}

    public function show(Room $room)
    {
        // On charge les lits et les patients admis sur ces lits
        $room->load(['service', 'beds.patient']);
        
        // Récupérer les patients actifs de cet hôpital pour l'assignation
        $patients = \App\Models\Patient::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('rooms.show', compact('room', 'patients'));
    }

    public function assign(Request $request, Room $room)
    {
        $request->validate([
            'bed_id' => 'required|exists:beds,id',
            'patient_id' => 'required|exists:patients,id',
        ]);

        $bed = Bed::findOrFail($request->bed_id);
        
        // Vérifier que le lit est disponible
        if (!$bed->is_available) {
            return back()->with('error', 'Ce lit est déjà occupé.');
        }

        // Créer une admission
        \App\Models\Admission::create([
            'hospital_id' => auth()->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'room_id' => $room->id,
            'bed_id' => $bed->id,
            'doctor_id' => auth()->id(),
            'admission_date' => now(),
            'status' => 'active',
        ]);

        // Marquer le lit comme occupé
        $bed->update(['is_available' => false]);

        return back()->with('success', 'Patient assigné avec succès.');
    }

    public function release(Request $request, Room $room)
    {
        $request->validate([
            'bed_id' => 'required|exists:beds,id',
        ]);

        $bed = Bed::findOrFail($request->bed_id);
        
        // Terminer l'admission active
        $admission = \App\Models\Admission::where('bed_id', $bed->id)
            ->where('status', 'active')
            ->first();

        if ($admission) {
            $admission->update([
                'status' => 'discharged',
                'discharge_date' => now(),
            ]);
        }

        // Libérer le lit
        $bed->update(['is_available' => true]);

        return back()->with('success', 'Lit libéré avec succès.');
    }
}