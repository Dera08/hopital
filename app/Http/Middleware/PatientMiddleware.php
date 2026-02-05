<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PatientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('patients')->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        // Vérifier si le patient est actif
        if (!Auth::guard('patients')->user()->is_active) {
            Auth::guard('patients')->logout();
            return redirect()->route('login')->with('error', 'Votre compte a été désactivé.');
        }

        return $next($request);
    }
}