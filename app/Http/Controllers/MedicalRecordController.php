<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\PatientVital;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    /**
     * Affiche uniquement les patients en attente (onglet Dossiers médicaux)
     */
    public function index()
    {
        $records = PatientVital::where(function($query) {
            $query->where('status', 'active')
                  ->orWhereNull('status'); // Pour inclure les anciens dossiers sans statut
        })
        ->where('status', '!=', 'admitted') // Exclure les patients admis
        ->orderBy('created_at', 'desc')
        ->get();

        return view('medical_records.index', compact('records'));
    }

    /**
     * Affiche uniquement les patients terminés (onglet Archives)
     */
    public function archivesIndex()
    {
        $records = PatientVital::where('status', 'archived')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('medical_records.index', compact('records'))->with('is_archive', true);
    }

   public function show($id)
{
    $record = PatientVital::findOrFail($id);
    
    $patientVitals = PatientVital::where('patient_ipu', $record->patient_ipu)
        ->orderBy('created_at', 'desc')
        ->get();

    // --- AJOUTEZ CES DEUX LIGNES ---
    $rooms = Room::where('is_active', true)->get();
    $availableBeds = Bed::with('room')->where('is_available', true)->whereNotNull('room_id')->whereHas('room')->get();
    // -------------------------------

    // Ajoutez 'rooms' et 'availableBeds' au compact
    return view('medical_records.show', compact('record', 'patientVitals', 'rooms', 'availableBeds'));
}
    /**
     * Affiche le formulaire d'édition d'un dossier médical
     */
    public function edit($id)
    {
        $record = PatientVital::findOrFail($id);

        return view('medical_records.edit', compact('record'));
    }

    /**
 * Met à jour le dossier avec les vraies valeurs saisies par l'infirmier
 */
public function update(Request $request, $id)
{
    $record = PatientVital::findOrFail($id);

    // On valide que les données arrivent bien du formulaire
    $validatedData = $request->validate([
        'temperature'    => 'required|numeric', // numeric pour éviter le texte
        'blood_pressure' => 'required|string',
        'pulse'          => 'required|numeric',
        'reason'         => 'required|string',
        'observations'   => 'nullable|string',
        'ordonnance'     => 'nullable|string',
        'is_visible_to_patient' => 'boolean',
    ]);

    // ÉTAPE CRUCIALE : On écrase les anciennes données (le fameux 37°C)
    // par ce que l'infirmier a tapé ($validatedData)
    $record->update($validatedData);

    // Pour que le carnet de santé ne soit plus vide, on synchronise avec la table Patient
    $patient = Patient::where('ipu', $record->patient_ipu)->first();
    if ($patient) {
        $patient->vitals()->create([
            'temperature' => $request->temperature,
            'pulse' => $request->pulse,
            'blood_pressure' => $request->blood_pressure,
            'patient_ipu' => $record->patient_ipu
        ]);
    }

    return redirect()->back()->with('success', 'Les constantes réelles ont été transmises !');
}

    /**
     * Archive un dossier médical
     */
    public function archive($id)
    {
        $record = PatientVital::findOrFail($id);

        // Archive tous les dossiers du même patient
        PatientVital::where('patient_name', $record->patient_name)
             ->where('patient_ipu', $record->patient_ipu)
            ->update(['status' => 'archived']);

        return redirect()->route('medical_records.index')
            ->with('success', 'Le dossier a été clôturé et transféré aux archives.');
    }

    /**
     * Admet un patient à l'hôpital
     */
    public function admit(Request $request, $id)
{
    $request->validate([
        'bed_id'  => 'required|exists:beds,id',
    ]);

    DB::transaction(function () use ($request, $id) {
        $record = PatientVital::findOrFail($id);

        if ($record->status === 'admitted') {
            throw new \Exception('Le patient est déjà hospitalisé.');
        }

        // ... (Ton code de création de patient reste identique) ...
        $patient = Patient::where('ipu', $record->patient_ipu)->first();
        if (!$patient) {
            $nameParts = explode(' ', $record->patient_name, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? $nameParts[0];

            $patient = Patient::create([
                'first_name' => $firstName,
                'name' => $lastName,
                'ipu' => $record->patient_ipu,
                'hospital_id' => $record->hospital_id,
                'service_id' => $record->service_id,
                'referring_doctor_id' => auth()->id(),
                'dob' => now()->subYears(30)->toDateString(),
                'gender' => 'Homme',
                'phone' => '0000000000',
            ]);
        }

        // 1. Vérifier la disponibilité du lit et récupérer la chambre
        $bed = \App\Models\Bed::findOrFail($request->bed_id);
        $room = $bed->room;

        if ($bed->is_available == false) {
            throw new \Exception('Le lit sélectionné n\'est plus disponible.');
        }

        // 2. Créer l'admission avec le lit
        Admission::create([
            'hospital_id' => $record->hospital_id,
            'patient_id' => $patient->id,
            'room_id' => $room->id,
            'bed_id' => $bed->id,
            'doctor_id' => auth()->id(),
            'admission_date' => now(),
            'admission_type' => 'emergency',
            'status' => 'active',
            'admission_reason' => $record->reason,
        ]);

        // 3. Mettre à jour les statuts
        $record->update(['status' => 'admitted']);

        $bed->update(['is_available' => false]); // Le lit est maintenant occupé

        // Optionnel : ne marquer la chambre occupée que si elle est pleine ?
        // Sinon, on garde ton code actuel :
        $room->update([
            'status' => 'occupied',
            'patient_vital_id' => $record->id,
        ]);
    });

    return redirect()->route('medical_records.index')
        ->with('success', 'Le patient a été admis et le lit a été réservé.');
}
    public function discharge($id)
    {
        DB::transaction(function () use ($id) {
            $admission = Admission::findOrFail($id);

            // Vérifier que l'admission est active
            if ($admission->status !== 'active') {
                throw new \Exception('Le patient n\'est pas hospitalisé.');
            }

            // Trouver la chambre occupée par ce patient
            $room = $admission->room;
            if ($room) {
                // Libérer la chambre
                $room->update([
                    'status' => 'available',
                    'patient_vital_id' => null,
                ]);
            }

            // Libérer le lit
            $bed = $admission->bed;
            if ($bed) {
                $bed->update(['is_available' => true]);
            }

            // Mettre à jour l'admission
            $admission->update([
                'status' => 'discharged',
                'discharge_date' => now(),
            ]);

            // Archiver le dossier médical (PatientVital)
            // Trouver le PatientVital lié à cette admission via le patient
            $patientVital = PatientVital::where('patient_ipu', $admission->patient->ipu ?? null)
                ->where('status', 'admitted')
                ->first();
            if ($patientVital) {
                $patientVital->update(['status' => 'archived']);
            }
        });

        return redirect()->route('medecin.dashboard')
            ->with('success', 'Le patient a été sorti avec succès.');
    }

    /**
     * Partager le dossier médical au patient
     */
    public function share($id)
    {
        $record = PatientVital::findOrFail($id);

        $record->update(['is_visible_to_patient' => true]);

        return redirect()->back()->with('success', 'Le dossier a été partagé au patient.');
    }

    /**
     * Supprimer un dossier médical
     */
    public function destroy($id)
    {
        $record = PatientVital::findOrFail($id);

        // Supprimer le dossier
        $record->delete();

        return redirect()->route('medical_records.index')
            ->with('success', 'Le dossier médical a été supprimé avec succès.');
    }
    
}
