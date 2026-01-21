<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdminVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté avec le guard superadmin
        if (!auth()->guard('superadmin')->check()) {
            return redirect()->route('superadmin.login');
        }

        // Vérifier si le SuperAdmin est vérifié (pas de code secret en attente)
        if (!$request->session()->has('superadmin_verified')) {
            return redirect()->route('superadmin.verify');
        }

        return $next($request);
    }
}
