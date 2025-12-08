<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            abort(401, 'Non authentifié');
        }

        $user = auth()->user();

        // Admin a accès à tout
        if ($user->isAdmin()) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        abort(403, 'Accès non autorisé pour ce rôle.');
    }
}