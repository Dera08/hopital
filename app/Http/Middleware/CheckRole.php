<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles La liste des rôles autorisés (ex: 'admin', 'doctor', 'nurse').
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // 1. Vérification de l'authentification
        if (!$user) {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            return redirect()->route('login');
        }
        
        // --- Amélioration de la sécurité et de la gestion de compte ---

        // 2. Vérifier si l'utilisateur est actif
        if (!$user->is_active) {
            auth()->logout();
            // Utilisation de `session()` pour un message flash clair
            return redirect()->route('login')->with('error', 'Votre compte a été désactivé.');
        }

        // --- Logique d'autorisation des rôles ---
        
        // 3. L'administrateur (si la méthode isAdmin existe) a accès à tout
        // J'ajoute 'admin' explicitement à la liste des rôles autorisés pour la clarté si vous n'avez pas isAdmin()
        $allowedRoles = array_map('strtolower', $roles);
        
        if (in_array('admin', $allowedRoles) || (method_exists($user, 'isAdmin') && $user->isAdmin())) {
             return $next($request);
        }

        // 4. Vérification si le rôle de l'utilisateur est dans la liste des rôles autorisés
        if (in_array($user->role, $allowedRoles)) {
            return $next($request);
        }

        // 5. Rôle non autorisé
        abort(403, 'Accès non autorisé pour ce rôle.');
    }
}