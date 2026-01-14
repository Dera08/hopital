<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExternalDoctorController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.external-doctor-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (!auth()->user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Votre compte a été désactivé.']);
            }

            $user = auth()->user();
            if ($user->role !== 'external_doctor') {
                Auth::logout();
                return back()->withErrors(['email' => 'Accès non autorisé pour les médecins externes.']);
            }

            return redirect()->intended(route('doctor.external.dashboard'));
        }

        return back()->withErrors(['email' => 'Les identifiants fournis sont incorrects.']);
    }

    public function dashboard()
    {
        $user = auth()->user();

        // Rendez-vous du jour pour le médecin externe
        $todayAppointments = $user->appointments()
            ->whereDate('appointment_datetime', today())
            ->with(['patient', 'service'])
            ->orderBy('appointment_datetime')
            ->get();

        // Statistiques
        $stats = [
            'today_appointments' => $todayAppointments->count(),
            'total_patients' => $user->appointments()->distinct('patient_id')->count(),
            'completed_today' => $user->appointments()
                ->whereDate('appointment_datetime', today())
                ->where('status', 'completed')
                ->count(),
        ];

        return view('medecin.external.dashboard', compact('todayAppointments', 'stats'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('external.login');
    }
}
