<?php

namespace App\Http\Controllers;

use App\Models\{Patient, Admission, Appointment, MedicalRecord, Prescription, ClinicalObservation, AuditLog, Hospital};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('ipu', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtres
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $patients = $query->latest()->paginate(20);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Homme,Femme,Other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        // Convert allergies string to array if present
        if (isset($validated['allergies']) && is_string($validated['allergies'])) {
            $validated['allergies'] = array_map('trim', explode(',', $validated['allergies']));
        }

        // Add hospital_id from authenticated user
        $validated['hospital_id'] = auth()->user()->hospital_id;

        DB::beginTransaction();
        try {
            // Génération de l'IPU unique
            $validated['ipu'] = Patient::generateIpu();

            $patient = Patient::create($validated);

            // Journalisation
            AuditLog::log('create', 'Patient', $patient->id, [
                'description' => 'Création du dossier patient',
                'new' => $patient->toArray()
            ]);

            DB::commit();

            return redirect()->route('patients.show', $patient)
                           ->with('success', 'Patient créé avec succès. IPU: ' . $patient->ipu);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du patient.']);
        }
    }

    public function show(Patient $patient)
  {
    // Charger les patientVitals pour l'historique
    $patientVitals = \App\Models\PatientVital::where('patient_ipu', $patient->ipu)
        ->orderBy('created_at', 'desc')
        ->get();

    // On ajoute 'clinicalObservations.user' pour charger les soins et le nom du médecin qui les a faits
    $patient->load(['clinicalObservations' => function($query) {
        $query->orderBy('observation_datetime', 'desc');
    }, 'clinicalObservations.user']);

    return view('patients.show', compact('patient', 'patientVitals'));
  }

    public function edit(Patient $patient)
    {
        $hospitals = Hospital::where('is_active', true)->get();
        return view('patients.edit', compact('patient', 'hospitals'));
    }

     public function update(Request $request, Patient $patient)
{
    \Log::info('Patient update method called', [
        'patient_id' => $patient->id,
        'request_data' => $request->all()
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'dob' => 'nullable|date|before:today',
        'gender' => 'nullable|in:Homme,Femme,Other',
        'hospital_id' => 'nullable|exists:hospitals,id',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_phone' => 'nullable|string|max:20',
        'blood_group' => 'nullable|string|max:5',
        'allergies' => 'nullable|string',
        'medical_history' => 'nullable|string',
        'is_active' => 'boolean',
    ]);

    // Convert allergies string to array if present
    if (isset($validated['allergies']) && is_string($validated['allergies'])) {
        $validated['allergies'] = array_map('trim', explode(',', $validated['allergies']));
    }

    DB::beginTransaction();
    try {
        $oldData = $patient->toArray();

        $patient->update($validated);

        // Journalisation
        AuditLog::log('update', 'Patient', $patient->id, [
            'description' => 'Mise à jour du dossier patient (Coordonnées & Allergies)',
            'old' => $oldData,
            'new' => $patient->toArray()
        ]);

        DB::commit();

        // On redirige vers l'onglet coordonnées spécifiquement après l'update
        return redirect()->to(route('patients.show', $patient) . '#tab-coords')
                         ->with('success', 'Dossier mis à jour avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
    }
   }

    public function destroy(Patient $patient)
    {
        // Soft delete
        DB::beginTransaction();
        try {
            AuditLog::log('delete', 'Patient', $patient->id, [
                'description' => 'Suppression (soft) du dossier patient',
                'old' => $patient->toArray()
            ]);

            $patient->delete();

            DB::commit();

            return redirect()->route('patients.index')
                           ->with('success', 'Patient supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    public function medicalFile(Patient $patient)
    {
        // Dossier médical complet (DPI)
        $user = auth()->user();

        // Vérification des permissions (seuls les médecins et infirmiers du même service)
        if (!$user->isAdmin() && !$user->isDoctor() && !$user->isNurse()) {
            abort(403, 'Accès non autorisé au dossier médical.');
        }

        // Charger toutes les données médicales
        $patient->load([
            'medicalRecords' => fn($q) => $q->with('recordedBy')->latest('record_datetime'),
            'prescriptions' => fn($q) => $q->with('doctor')->latest(),
            'clinicalObservations' => fn($q) => $q->with('user')->latest('observation_datetime'),
            'documents' => fn($q) => $q->where('is_validated', true)->latest(),
        ]);

        // Journalisation de l'accès
        AuditLog::log('view', 'Patient', $patient->id, [
            'description' => 'Consultation du dossier médical complet',
        ]);

        return view('patients.medical-file', compact('patient'));
    }

    public function quickSearch(Request $request)
    {
        // Recherche AJAX rapide pour l'autocomplétion
        $search = $request->input('q');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $patients = Patient::where('is_active', true)
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('ipu', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'ipu', 'name', 'first_name', 'dob', 'phone']);

        return response()->json($patients->map(function($patient) {
            return [
                'id' => $patient->id,
                'label' => $patient->full_name . ' (' . $patient->ipu . ')',
                'ipu' => $patient->ipu,
                'name' => $patient->full_name,
                'dob' => $patient->dob->format('d/m/Y'),
                'age' => $patient->age,
            ];
        }));
    }
    public function archive(\App\Models\Patient $patient)
{
    // On utilise la colonne 'is_active' que j'ai vue dans vos logs SQL
    $patient->update([
        'is_active' => false 
    ]);

    // On redirige vers le dashboard du médecin avec un message
    return redirect()->route('medecin.dashboard')
                     ->with('success', 'Le dossier du patient ' . $patient->name . ' a été archivé.');
 } 
}
