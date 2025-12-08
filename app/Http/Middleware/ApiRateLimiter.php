<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimiter
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            \App\Models\AuditLog::log(
                'rate_limit_exceeded',
                'System',
                null,
                ['description' => 'Tentative de dépassement du rate limit']
            );

            abort(429, 'Trop de requêtes. Veuillez réessayer dans quelques instants.');
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }

    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip() .
            '|' . (auth()->id() ?? 'guest')
        );
    }
}