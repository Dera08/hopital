<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\{Appointment, MedicalRecord, Prescription, Invoice};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientPortalController extends Controller 
{
    public function __construct()
    {
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
    public function showBookAppointmentForm()
{
    $patient = Auth::guard('patients')->user();
    
    // 1. Récupérer tous les hôpitaux
    $hospitals = \App\Models\Hospital::all();

    // 2. Préparer les services groupés par hôpital pour le JavaScript
    // On récupère les services (ou prestations) liés aux hôpitaux
    $allServices = \App\Models\Service::all(); 

    $servicesAndPrestations = [];
    foreach ($allServices as $service) {
        // On crée un tableau où la clé est l'ID de l'hôpital
        $servicesAndPrestations[$service->hospital_id][] = [
            'id' => $service->id,
            'name' => $service->name,
            'price' => $service->price
        ];
    }

    return view('patients.auth.book-appointment', compact('patient', 'hospitals', 'servicesAndPrestations'));
}
public function bookAppointment()
{
    $patient = Auth::guard('patients')->user(); //
    $hospitals = \App\Models\Hospital::where('is_active', true)->get(); //

    $servicesAndPrestations = []; //

    foreach ($hospitals as $hospital) {
        // Récupérer les services rattachés à cet hôpital
        $hospitalServices = \App\Models\Service::where('hospital_id', $hospital->id)
            ->where('is_active', true)
            ->get(); //

        $mergedList = []; //

        foreach ($hospitalServices as $service) {
            $mergedList[] = [
                'id'    => $service->id,
                'name'  => $service->name,
                'price' => $service->consultation_price ?? 0, // Utilise ta colonne SQL exacte
                'type'  => 'service'
            ]; //
        }

        // Récupérer aussi les prestations (si tu en as)
        $hospitalPrestations = \App\Models\Prestation::where('hospital_id', $hospital->id)
            ->where('is_active', true)
            ->get(); //

        foreach ($hospitalPrestations as $prestation) {
            $mergedList[] = [
                'id'    => $prestation->id,
                'name'  => $prestation->name,
                'price' => $prestation->price ?? 0,
                'type'  => 'prestation'
            ]; //
        }

        // CRUCIAL : On indexe le tableau par l'ID de l'hôpital pour le JS
        $servicesAndPrestations[$hospital->id] = $mergedList; //
    }

    return view('portal.book-appointment', compact('patient', 'hospitals', 'servicesAndPrestations')); //
}

    /**
     * Récupérer les prestations de consultation d'un hôpital via AJAX
     */
    public function getHospitalServices($hospitalId)
    {
        $prestations = \App\Models\Prestation::where('hospital_id', $hospitalId)
            ->where('category', 'consultation')
            ->where('is_active', true)
            ->get()
            ->map(function($prestation) {
                return [
                    'id' => $prestation->id,
                    'name' => $prestation->name,
                    'price' => $prestation->price,
                ];
            });

        return response()->json($prestations);
    }

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

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $patient->update($validated);

        return back()->with('success', 'Vos informations ont été mises à jour avec succès.');
    }

    public function medicalHistory()
    {
        $patient = Auth::guard('patients')->user();
        
        $records = $patient->medicalRecords()
            ->with(['doctor', 'service', 'hospital'])
            ->latest()
            ->paginate(10);

        return view('portal.medical-history', compact('records'));
    }

    public function prescriptions()
    {
        $patient = Auth::guard('patients')->user();
        $prescriptions = $patient->prescriptions()->with('doctor')->latest()->paginate(10);
        return view('portal.prescriptions', compact('prescriptions'));
    }

    public function invoices()
    {
        $patient = Auth::guard('patients')->user();
        $invoices = Invoice::where('patient_id', $patient->id)->latest()->paginate(10);
        return view('portal.invoices', compact('invoices'));
    }

    public function messaging()
    {
        $patient = Auth::guard('patients')->user();
        $conversations = []; 
        return view('portal.messaging', compact('conversations'));
    }

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
            'hospital_id' => 'required|exists:hospitals,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'service_or_prestation_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $service = \App\Models\Service::find($value);
                    $prestation = \App\Models\Prestation::find($value);
                    if (!$service && !$prestation) {
                        $fail('Le service ou la prestation sélectionné n\'existe pas.');
                    }
                },
            ],
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'home_address' => 'required_if:consultation_type,home|nullable|string',
        ]);

        $patient = Auth::guard('patients')->user();

        // Combiner date et heure
        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];

        // Déterminer si c'est un service ou une prestation
        $serviceOrPrestationId = $validated['service_or_prestation_id'];
        $serviceId = null;
        $prestationId = null;

        // Vérifier si c'est un service
        $service = \App\Models\Service::find($serviceOrPrestationId);
        if ($service) {
            $serviceId = $service->id;
        } else {
            // C'est une prestation
            $prestationId = $serviceOrPrestationId;
        }

        // Créer le rendez-vous
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'service_id' => $serviceId,
            'appointment_datetime' => $appointmentDateTime,
            'status' => 'pending',
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'consultation_type' => $validated['consultation_type'],
            'home_address' => $validated['home_address'] ?? null,
            'hospital_id' => $validated['hospital_id'],
        ]);

        // Attach the prestation to the appointment if it's a prestation
        if ($prestationId) {
            $appointment->prestations()->attach($prestationId);
        }

        return redirect()->route('patient.appointments')
            ->with('success', 'Votre demande de rendez-vous a été enregistrée. Vous serez contacté pour confirmation.');
    }
}