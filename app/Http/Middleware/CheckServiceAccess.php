<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckServiceAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $next($request);
        }

        if (!$user->service_id) {
            abort(403, 'Aucun service assigné. Contactez l\'administrateur.');
        }

        $resourceId = $request->route()->parameter('patient') 
                   ?? $request->route()->parameter('admission')
                   ?? $request->route()->parameter('appointment');

        if ($resourceId) {
            if (!$this->hasServiceAccess($user, $resourceId, $request->route()->getName())) {
                abort(403, 'Accès refusé : cette ressource n\'appartient pas à votre service.');
            }
        }

        return $next($request);
    }

    private function hasServiceAccess($user, $resourceId, $routeName): bool
    {
        if (str_contains($routeName, 'patients')) {
            $patient = \App\Models\Patient::find($resourceId);
            if (!$patient) return false;

            return $patient->admissions()
                ->where('status', 'active')
                ->whereHas('room', function($q) use ($user) {
                    $q->where('service_id', $user->service_id);
                })
                ->exists();
        }

        if (str_contains($routeName, 'appointments')) {
            $appointment = \App\Models\Appointment::find($resourceId);
            return $appointment && $appointment->service_id === $user->service_id;
        }

        if (str_contains($routeName, 'admissions')) {
            $admission = \App\Models\Admission::find($resourceId);
            if (!$admission) return false;
            
            return $admission->room && $admission->room->service_id === $user->service_id;
        }

        return true;
    }
}