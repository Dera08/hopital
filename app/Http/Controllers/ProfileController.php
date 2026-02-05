<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        if ($user->role === 'admin') {
            return view('admin.profile', [
                'user' => $user,
            ]);
        }

        if ($user->isDoctor()) {
            $user->load(['service', 'hospital']);
            $availability = \App\Models\DoctorAvailability::where('doctor_id', $user->id)
                ->where('hospital_id', $user->hospital_id)
                ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
                ->get();
                
            return view('medecin.profile', [
                'user' => $user,
                'availability' => $availability
            ]);
        }
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Initialise le planning par défaut du médecin.
     */
    public function initializeAvailability(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        if (!$user->isDoctor()) {
            return redirect()->route('profile.edit')->with('error', 'Action non autorisée.');
        }

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $hospitalId = $user->hospital_id;

        foreach ($days as $day) {
            \App\Models\DoctorAvailability::updateOrCreate(
                [
                    'doctor_id' => $user->id,
                    'day_of_week' => $day,
                    'hospital_id' => $hospitalId,
                ],
                [
                    'start_time' => '08:00',
                    'end_time' => '16:00',
                    'slot_duration' => 30,
                    'is_active' => true,
                ]
            );
        }

        return redirect()->route('profile.edit')->with('success', 'Votre planning a été initialisé avec les horaires par défaut (08h00 - 16h00).');
    }

    /**
     * Met à jour un créneau de disponibilité.
     */
    public function updateAvailability(Request $request): RedirectResponse
    {
        $request->validate([
            'slot_id' => 'required|exists:doctor_availability,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'is_active' => 'nullable',
        ]);

        $user = auth()->user();
        $slot = \App\Models\DoctorAvailability::where('doctor_id', $user->id)->findOrFail($request->slot_id);

        $slot->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Disponibilité mise à jour.');
    }

    /**
     * Alterne l'état d'un créneau.
     */
    public function toggleAvailabilitySlot($id): RedirectResponse
    {
        $user = auth()->user();
        $slot = \App\Models\DoctorAvailability::where('doctor_id', $user->id)->findOrFail($id);

        $slot->update(['is_active' => !$slot->is_active]);

        return redirect()->route('profile.edit')->with('success', 'Statut du créneau mis à jour.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
