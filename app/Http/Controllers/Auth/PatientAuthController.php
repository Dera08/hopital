<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientAuthController extends Controller
{

    public function showLoginForm()
    {
        return view('patients.auth.login-patient');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('patients')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('patient.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    // Afficher le formulaire d'inscription
    public function showRegistrationForm()
    {
        $hospitals = \App\Models\Hospital::where('is_active', true)->get();
        return view('patients.auth.register-patient', compact('hospitals'));
    }

    public function register(Request $request)
{
    // 1. Validation
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:patients,email',
        'phone' => 'required|string|max:20',
        'date_of_birth' => 'required|date|before:today',
        'hospital_id' => 'nullable|exists:hospitals,id',
        'password' => 'required|string|min:8|confirmed',
        'terms' => 'required|accepted',
    ]);

    // 2. Mapping et Création (On lie le HTML au Modèle)
    $patient = Patient::create([
        'ipu' => Patient::generateIpu(),
        'first_name' => $validated['first_name'],
        'name' => $validated['last_name'], // Transforme last_name en name pour la BDD
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'dob' => $validated['date_of_birth'], // Transforme date_of_birth en dob pour la BDD
        'password' => Hash::make($validated['password']),
        'is_active' => true,
        'hospital_id' => $validated['hospital_id'] ?? null,
    ]);

    // 4. Connexion
    Auth::guard('patients')->login($patient);

    return redirect()->route('patient.dashboard')
        ->with('success', 'Bienvenue ! Votre IPU est le : ' . $patient->ipu);
}

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::guard('patients')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}