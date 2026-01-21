<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    
    use AuthenticatesUsers;
/**
 * Intercepte la connexion pour le Super Admin
 */
public function login(Request $request)
{
    // On récupère les accès définis dans le .env
    $superAdminEmail = env('SUPER_ADMIN_EMAIL');
    $superAdminPassword = env('SUPER_ADMIN_PASSWORD');
    $superAdminCode = env('SUPER_ADMIN_ACCESS_CODE');

    // Si l'email saisi correspond au Super Admin
    if ($request->email === $superAdminEmail) {
        
        // On vérifie le mot de passe et le code secret
        if ($request->password === $superAdminPassword && $request->access_code === $superAdminCode) {
            
            // On cherche l'utilisateur Super Admin en base (il doit exister avec le rôle 'super_admin')
            $user = \App\Models\User::where('email', $superAdminEmail)->first();

            if ($user) {
                auth()->login($user);
                return $this->authenticated($request, $user);
            }
        }

        // Si l'un des codes est faux, on renvoie une erreur spécifique
        throw ValidationException::withMessages([
            'email' => ['Accès Super Admin refusé. Vérifiez vos codes de sécurité.'],
        ]);
    }

    // Si ce n'est pas l'email du Super Admin, on laisse Laravel gérer normalement
    $this->validateLogin($request);

    if ($this->attemptLogin($request)) {
        return $this->sendLoginResponse($request);
    }

    return $this->sendFailedLoginResponse($request);
}
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