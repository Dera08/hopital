<?php

namespace App\Http\Controllers;

use App\Models\{User, Service, AuditLog, Patient, Appointment, Admission, Invoice};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, DB};
use Carbon\Carbon;

 // ============ PORTAL CONTROLLER (Portail Patient) ============
class PortalController extends Controller
{
    public function dashboard()
    {
        $patient = auth()->guard('patients')->user();

        // Prochains rendez-vous
        $upcomingAppointments = $patient->appointments()
            ->where('appointment_datetime', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_datetime')
            ->limit(5)
            ->get();

        // Documents validés
        $documents = $patient->documents()
            ->where('is_validated', true)
            ->latest()
            ->limit(10)
            ->get();

        return view('portal.dashboard', compact('upcomingAppointments', 'documents'));
    }

    public function appointments()
    {
        $patient = auth()->guard('patients')->user();

        $appointments = $patient->appointments()
            ->with(['doctor', 'service'])
            ->latest('appointment_datetime')
            ->paginate(20);

        return view('portal.appointments', compact('appointments'));
    }

    public function bookAppointment(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_datetime' => 'required|date|after:now',
            'reason' => 'nullable|string|max:500',
        ]);

        $patient = auth()->guard('patients')->user();

        try {
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $validated['doctor_id'],
                'service_id' => $validated['service_id'],
                'appointment_datetime' => $validated['appointment_datetime'],
                'duration' => 30,
                'status' => 'scheduled',
                'type' => 'consultation',
                'reason' => $validated['reason'],
            ]);

            return back()->with('success', 'Rendez-vous pris avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la prise de rendez-vous.']);
        }
    }

    public function cancelAppointment(Appointment $appointment)
    {
        $patient = auth()->guard('patients')->user();

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Non autorisé.');
        }

        // Vérifier si on peut annuler (au moins 24h avant)
        if ($appointment->appointment_datetime->diffInHours(now()) < 24) {
            return back()->withErrors(['error' => 'Impossible d\'annuler un rendez-vous moins de 24h avant.']);
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Rendez-vous annulé.');
    }

    public function documents()
    {
        $patient = auth()->guard('patients')->user();

        $documents = $patient->documents()
            ->where('is_validated', true)
            ->with('uploadedBy')
            ->latest()
            ->paginate(20);

        return view('portal.documents', compact('documents'));
    }

    public function profile()
    {
        $patient = auth()->guard('patients')->user();

        return view('portal.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patient = auth()->guard('patients')->user();

        $validated = $request->validate([
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $patient->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}