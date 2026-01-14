<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Logique après connexion réussie
     */
    protected function authenticated(Request $request, $user)
    {
        // --- ÉTAPE CRUCIALE POUR LE MULTI-HÔPITAL ---
        // On stocke l'ID de l'hôpital de l'utilisateur en session.
        // C'est ce qui permettra à notre "Trait" de filtrer les données automatiquement.
        Session::put('hospital_id', $user->hospital_id);

        // 1. Redirection pour l'Admin
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        // 2. Redirection pour le Médecin
        if ($user->role === 'doctor') {
            return redirect()->route('medecin.dashboard');
        }

        // 3. Redirection pour l'Infirmier
        if ($user->role === 'nurse') {
            return redirect()->route('nurse.dashboard');
        }

        // Par défaut
        return redirect()->intended('/home');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}