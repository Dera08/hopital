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
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('patients.auth.login');
    }

    // Traiter la connexion
  public function login(Request $request)
{
    // Log de début
    \Log::info('=== DÉBUT TENTATIVE CONNEXION PATIENT ===');
    \Log::info('Données reçues', [
        'identifier' => $request->identifier,
        'remember' => $request->filled('remember'),
        'url_actuelle' => url()->current(),
    ]);

    $request->validate([
        'identifier' => 'required|string',
        'password' => 'required|string',
    ]);

    // Recherche du patient SANS le global scope
    $patient = Patient::withoutGlobalScope('hospital')
        ->where(function($query) use ($request) {
            $query->where('email', $request->identifier)
                  ->orWhere('ipu', $request->identifier);
        })
        ->where('is_active', true)
        ->first();

    \Log::info('Résultat recherche patient', [
        'patient_trouvé' => $patient ? 'OUI' : 'NON',
        'patient_id' => $patient?->id ?? 'N/A',
        'patient_email' => $patient?->email ?? 'N/A',
        'patient_nom' => $patient?->full_name ?? 'N/A',
    ]);

    if (!$patient) {
        \Log::error('Patient NON TROUVÉ');
        return back()->withErrors([
            'identifier' => 'Aucun patient trouvé avec cet identifiant.',
        ])->withInput();
    }

    // Vérification du mot de passe
    $passwordCheck = Hash::check($request->password, $patient->password);
    \Log::info('Vérification mot de passe', [
        'mot_de_passe_valide' => $passwordCheck ? 'OUI' : 'NON'
    ]);

    if (!$passwordCheck) {
        \Log::error('Mot de passe INCORRECT');
        return back()->withErrors([
            'identifier' => 'Mot de passe incorrect.',
        ])->withInput();
    }

    // Tentative de connexion
    \Log::info('Tentative de connexion avec le guard patients');
    
    try {
        Auth::guard('patients')->login($patient, $request->filled('remember'));
        $request->session()->regenerate();
        
        \Log::info('Connexion réussie !', [
            'patient_connecté' => Auth::guard('patients')->check() ? 'OUI' : 'NON',
            'patient_id' => Auth::guard('patients')->id(),
        ]);
        
        \Log::info('Redirection vers patient.dashboard');
        
        return redirect()->route('patient.dashboard')
            ->with('success', 'Bienvenue, ' . $patient->first_name . ' !');
            
    } catch (\Exception $e) {
        \Log::error('ERREUR lors de la connexion', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->withErrors([
            'identifier' => 'Une erreur est survenue lors de la connexion.',
        ])->withInput();
    }
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

        return redirect()->route('patient.login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}