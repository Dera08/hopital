<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MFAController extends Controller
{
    /**
     * Show the MFA setup form.
     */
    public function setup(Request $request): View
    {
        $user = $request->user();

        // Return different views based on user role
        if ($user->role === 'admin') {
            return view('admin.mfa-setup', [
                'user' => $user,
            ]);
        }

        return view('auth.mfa-setup', [
            'user' => $user,
        ]);
    }

    /**
     * Enable MFA for the user.
     */
    public function enable(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Génération du secret MFA (utiliser Google2FA en production)
        $secret = \Illuminate\Support\Str::random(32);

        $user->update([
            'mfa_enabled' => true,
            'mfa_secret' => encrypt($secret),
        ]);

        \App\Models\AuditLog::log('mfa_enabled', 'User', $user->id, [
            'description' => 'Activation de l\'authentification MFA',
        ]);

        return redirect()->route('settings')->with('success', 'MFA activé avec succès.');
    }

    /**
     * Disable MFA for the user.
     */
    public function disable(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'mfa_enabled' => false,
            'mfa_secret' => null,
        ]);

        \App\Models\AuditLog::log('mfa_disabled', 'User', $user->id, [
            'description' => 'Désactivation de l\'authentification MFA',
        ]);

        return redirect()->route('settings')->with('success', 'MFA désactivé avec succès.');
    }
}