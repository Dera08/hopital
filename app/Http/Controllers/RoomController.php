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
        'room_number' => $request->room_number,
        'service_id'  => $request->service_id,
        'capacity'    => $request->capacity,
        'hospital_id' => auth()->user()->hospital_id, // Sécurité : lié à l'hôpital de l'admin
    ]);

    // 3. Création automatique des lits (La boucle magique)
    for ($i = 1; $i <= $request->capacity; $i++) {
        \App\Models\Bed::create([
            'room_id'      => $room->id,
            'bed_number'   => "Lit " . $i, // Nomme les lits : Lit 1, Lit 2...
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
        return view('rooms.show', compact('room'));
    }
}