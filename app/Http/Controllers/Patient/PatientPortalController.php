<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\{Appointment, MedicalRecord, Prescription, Invoice};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientPortalController extends Controller 
{
    public function __construct()
    {
        // Applique le middleware d'authentification pour la garde 'patients'
        $this->middleware('auth:patients'); 
    }

    public function dashboard()
{
    \Log::info('=== ACCÈS AU DASHBOARD PATIENT ===');
    \Log::info('Guard patients authentifié ?', [
        'authentifié' => Auth::guard('patients')->check() ? 'OUI' : 'NON',
        'patient_id' => Auth::guard('patients')->id() ?? 'N/A',
    ]);

    $patient = Auth::guard('patients')->user();

    if (!$patient) {
        \Log::error('AUCUN PATIENT CONNECTÉ - Redirection vers login');
        return redirect()->route('patient.login');
    }

    \Log::info('Patient trouvé', [
        'id' => $patient->id,
        'nom' => $patient->full_name,
        'email' => $patient->email,
    ]);

    // Chargement des relations
    $patient->load([
        'referringDoctor',
        'prescriptions' => fn($query) => $query->latest()->take(3),
        'medicalRecords' => fn($query) => $query->latest()->take(3),
        'appointments' => fn($query) => $query->latest()->take(5)
    ]);

    $totalAppointments = $patient->appointments()->count();
    $totalPrescriptions = $patient->prescriptions()->count();

    $upcomingAppointments = $patient->appointments()
        ->where('appointment_datetime', '>', now())
        ->where('status', 'confirmed')
        ->orderBy('appointment_datetime')
        ->with(['doctor', 'service'])
        ->take(3)
        ->get();

    $recentRecords = $patient->medicalRecords()
        ->with('doctor')
        ->latest()
        ->take(5)
        ->get();

    \Log::info('Chargement de la vue dashboard');

    return view('patients.auth.dashboard', compact(
        'patient', 
        'upcomingAppointments', 
        'recentRecords', 
        'totalAppointments', 
        'totalPrescriptions'
    ));
}
    public function appointments()
    {
        $patient = Auth::guard('patients')->user();

        $appointments = $patient->appointments()
            ->with(['doctor', 'service'])
            ->latest()
            ->paginate(10);

        return view('portal.appointments', compact('appointments'));
    }

    /**
     * Prendre un rendez-vous
     * Vue : resources/views/portal/book-appointment.blade.php
     */
    public function bookAppointment()
{
    $patient = Auth::guard('patients')->user();

    // Récupérer les hôpitaux actifs
    $hospitals = \App\Models\Hospital::where('is_active', true)->get();

    // Récupérer les services par hôpital avec leur prix de consultation
    $services = [];
    foreach ($hospitals as $hospital) {
        // Récupérer les services de cet hôpital
        $hospitalServices = $hospital->services()
            ->where('is_active', true)
            ->get();

        \Log::info("Hospital {$hospital->id} ({$hospital->name}) has " . $hospitalServices->count() . " services");

        $servicesWithPrice = [];
        foreach ($hospitalServices as $service) {
            \Log::info("Service: {$service->name} (ID: {$service->id}, Price: {$service->consultation_price})");
            $servicesWithPrice[] = [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->consultation_price ?? 0,
            ];
        }

        $services[$hospital->id] = $servicesWithPrice;
    }

    // Log pour debug
    \Log::info('Services chargés pour les hôpitaux: ' . json_encode($services));
    \Log::info('Nombre d\'hôpitaux: ' . $hospitals->count());
    \Log::info('Services par hôpital: ' . json_encode(array_map(function($services) { return count($services); }, $services)));

    return view('portal.book-appointment', compact('patient', 'hospitals', 'services'));
}

    /**
     * Profil du patient
     * Vue : resources/views/patients/edit.blade.php (selon tes dossiers)
     */
    public function profile()
{
    $patient = Auth::guard('patients')->user();
    $hospitals = \App\Models\Hospital::where('is_active', true)->get();
    return view('portal.profile', compact('patient', 'hospitals'));
}

public function updateProfile(Request $request)
{
    $patient = Auth::guard('patients')->user();

    $validated = $request->validate([
        'phone' => 'required|string|max:20',
        'email' => 'required|email|unique:patients,email,' . $patient->id, 
        'address' => 'nullable|string',
        'city' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:10',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_phone' => 'nullable|string|max:20',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    // Si un nouveau mot de passe est fourni, le hacher
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }

    $patient->update($validated);

    return back()->with('success', 'Vos informations ont été mises à jour avec succès.');
}
    /**
     * Historique médical
     * Vue : resources/views/portal/medical-history.blade.php
     */
    /**
 * Historique médical
 * Vue : resources/views/portal/medical-history.blade.php
 */
public function medicalHistory()
{
    $patient = Auth::guard('patients')->user();
    
    // On utilise le nom 'records' pour correspondre à la vue professionnelle
    $records = $patient->medicalRecords()
        ->with(['doctor', 'service', 'hospital']) // Eager loading pour la performance
        ->latest()
        ->paginate(10);

    return view('portal.medical-history', compact('records'));
}

    /**
     * Ordonnances
     * Vue : resources/views/portal/prescriptions.blade.php
     */
    public function prescriptions()
    {
        $patient = Auth::guard('patients')->user();
        $prescriptions = $patient->prescriptions()->with('doctor')->latest()->paginate(10);
        return view('portal.prescriptions', compact('prescriptions'));
    }

    /**
     * Factures
     * Vue : resources/views/portal/invoices.blade.php
     */
    public function invoices()
    {
        $patient = Auth::guard('patients')->user();
        $invoices = Invoice::where('patient_id', $patient->id)->latest()->paginate(10);
        return view('portal.invoices', compact('invoices'));
    }

    /**
     * Messagerie
     * Vue : resources/views/portal/messaging.blade.php
     */
    public function messaging()
    {
        $patient = Auth::guard('patients')->user();
        $conversations = []; 
        return view('portal.messaging', compact('conversations'));
    }

    /**
     * Documents
     * Vue : resources/views/portal/documents.blade.php
     */
    public function documents()
    {
        $patient = Auth::guard('patients')->user();
        $documents = $patient->documents()->latest()->paginate(10);
        return view('portal.documents', compact('documents'));
    }
    public function storeAppointment(Request $request)
{
    $validated = $request->validate([
        'consultation_type' => 'required|in:hospital,home',
        'appointment_date' => 'required|date|after:today',
        'appointment_time' => 'required',
        'service_id' => 'required|exists:services,id',
        'reason' => 'required|string|max:500',
        'notes' => 'nullable|string|max:1000',
        'home_address' => 'required_if:consultation_type,home|nullable|string',
    ]);

    $patient = Auth::guard('patients')->user();

    // Combiner date et heure
    $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];

    // Créer le rendez-vous
    Appointment::create([
        'patient_id' => $patient->id,
        'service_id' => $validated['service_id'],
        'appointment_datetime' => $appointmentDateTime,
        'status' => 'pending_payment',
        'reason' => $validated['reason'],
        'notes' => $validated['notes'] ?? null,
        'consultation_type' => $validated['consultation_type'],
        'home_address' => $validated['home_address'] ?? null,
        'hospital_id' => $patient->hospital_id,
    ]);

    return redirect()->route('patient.appointments')
        ->with('success', 'Votre demande de rendez-vous a été enregistrée. Vous serez contacté pour confirmation.');
}
}