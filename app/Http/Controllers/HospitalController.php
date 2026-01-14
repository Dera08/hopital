<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    /**
     * Affiche la page de sélection d'hôpital
     */
    public function selectHospital()
    {
        $hospitals = Hospital::where('is_active', true)->get();
        return view('auth.select-hospital', compact('hospitals'));
    }

    /**
     * Traite la recherche d'hôpital et redirige vers la page appropriée
     */
    public function processHospitalSelection(Request $request)
    {
        $request->validate([
            'hospital_search' => 'required|string|max:255'
        ]);

        $search = strtolower(trim($request->hospital_search));

        // Recherche par slug exact
        $hospital = Hospital::where('slug', $search)->where('is_active', true)->first();

        // Si pas trouvé par slug, recherche par nom
        if (!$hospital) {
            $hospital = Hospital::whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                              ->where('is_active', true)
                              ->first();
        }

        if (!$hospital) {
            return back()->withErrors(['hospital_search' => 'Établissement non trouvé. Vérifiez le nom ou le code.']);
        }

        // Rediriger vers le formulaire d'inscription de l'hôpital
        return redirect()->route('register', $hospital->slug);
    }

    /**
     * Affiche la page de connexion pour un hôpital spécifique
     */
    public function showLogin($hospital_slug)
    {
        $hospital = Hospital::where('slug', $hospital_slug)->where('is_active', true)->firstOrFail();

        return view('auth.login', compact('hospital'));
    }

    /**
     * Affiche le formulaire d'inscription pour un hôpital spécifique
     */
    public function showRegistration($hospital_slug)
    {
        $hospital = Hospital::where('slug', $hospital_slug)->where('is_active', true)->firstOrFail();

        // Récupère les services de cet hôpital
        $services = $hospital->services()->where('is_active', true)->get();

        return view('auth.register', compact('hospital', 'services'));
    }

    /**
     * Affiche le formulaire de connexion général
     */
    public function showGeneralLogin()
    {
        return view('auth.login');
    }

    /**
     * Traite la connexion générale (staff et patients)
     */
    public function processGeneralLogin(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->identifier;
        $password = $request->password;
        $remember = $request->boolean('remember');

        // Essayer d'abord la connexion staff (utilisateur)
        $user = \App\Models\User::where('email', $identifier)->first();

        if ($user && auth()->attempt(['email' => $identifier, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            if (!$user->is_active) {
                auth()->logout();
                return back()->withErrors(['identifier' => 'Votre compte a été désactivé.']);
            }

            // Redirection selon le rôle
            return match($user->role) {
                'doctor' => redirect()->route('medecin.dashboard'),
                'nurse' => redirect()->route('nurse.dashboard'),
                'internal_doctor' => redirect()->route('doctor.internal.dashboard'),
                'external_doctor' => redirect()->route('doctor.external.dashboard'),
                'cashier' => redirect()->route('cashier.dashboard'),
                default => redirect()->intended(route('dashboard'))
            };
        }

        // Si échec, essayer la connexion patient
        $patient = \App\Models\Patient::where('ipu', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();

        if ($patient && auth()->guard('patients')->attempt([
            'email' => $patient->email,
            'password' => $password
        ], $remember)) {
            $request->session()->regenerate();
            return redirect()->route('patient.dashboard');
        }

        // Si les deux échouent
        return back()->withErrors(['identifier' => 'Les identifiants fournis sont incorrects.']);
    }
}
