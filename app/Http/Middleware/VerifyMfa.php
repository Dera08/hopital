<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyMfa
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->mfa_enabled) {
            if (!session('mfa_verified')) {
                return redirect()->route('mfa.verify');
            }
        }

        return $next($request);
    }
}