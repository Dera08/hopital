namespace App\Http\Controllers\Concerns;

trait HasPermissionChecks
{
    protected function canAccessPatient($patientId): bool
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor() || $user->isNurse()) {
            // Vérifier si le patient est dans le service de l'utilisateur
            $patient = \App\Models\Patient::find($patientId);
            
            return $patient && $patient->admissions()
                ->where('status', 'active')
                ->whereHas('room', function($q) use ($user) {
                    $q->where('service_id', $user->service_id);
                })
                ->exists();
        }

        return false;
    }

    protected function canModifyMedicalRecord($recordId): bool
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return true;
        }

        $record = \App\Models\MedicalRecord::find($recordId);
        
        if (!$record) {
            return false;
        }

        // Seul le créateur ou un médecin du même service peut modifier
        if ($record->recorded_by_id === $user->id) {
            return true;
        }

        if ($user->isDoctor() && $this->canAccessPatient($record->patient_id)) {
            return true;
        }

        return false;
    }

    protected function canPrescribe(): bool
    {
        return auth()->user()->isDoctor();
    }

    protected function canValidateDocument(): bool
    {
        return auth()->user()->isDoctor();
    }
}