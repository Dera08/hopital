<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogSensitiveAccess
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->is('patients/*') || 
            $request->is('medical-records/*') || 
            $request->is('prescriptions/*')) {
            
            \App\Models\AuditLog::log(
                'access',
                $this->getResourceType($request->path()),
                $this->getResourceId($request->path()),
                ['description' => 'Accès à une ressource sensible']
            );
        }

        return $response;
    }

    private function getResourceType(string $path): string
    {
        if (str_contains($path, 'patients')) return 'Patient';
        if (str_contains($path, 'medical-records')) return 'MedicalRecord';
        if (str_contains($path, 'prescriptions')) return 'Prescription';
        return 'Unknown';
    }

    private function getResourceId(string $path): ?int
    {
        preg_match('/\/(\d+)/', $path, $matches);
        return $matches[1] ?? null;
    }
}