<?php

namespace App\Policies;

use App\Models\{User, Patient};

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés
    }
public function view(User $user, Patient $patient): bool
{
    // 1. Règle d'accès la plus élevée : Admin
    if ($user->isAdmin()) {
        return true;
    }

    // 2. Règle stricte pour le Médecin Externe (Override tout autre accès)
    if ($user->hasRole('external_doctor')) {
        // Autorise UNIQUEMENT s'il est le médecin référent
        return $patient->referring_doctor_id === $user->id;
    }

    // 3. Règle pour le Staff Interne (Médecin Interne, Infirmière, Administratif)
    // Nous utilisons hasRole car ces rôles sont plus spécifiques que isDoctor() ou isNurse().
    if ($user->hasRole('internal_doctor') || $user->hasRole('nurse') || $user->hasRole('administrative')) {
        
        // Autorisation 3a : Accès si l'utilisateur est lié au service du patient ADMIS
        if ($user->service_id) {
            $isPatientInService = $patient->admissions()
                ->where('status', 'active')
                ->whereHas('room', fn($q) => $q->where('service_id', $user->service_id))
                ->exists();
            
            if ($isPatientInService) {
                return true;
            }
        }

        // Autorisation 3b : Le patient est NON ADMIS (consultation, suivi administratif)
        // Tous les internes (sauf l'Externe) devraient pouvoir voir les patients non-admis
        $isActiveAdmission = $patient->admissions()->where('status', 'active')->exists();
        
        if (!$isActiveAdmission) {
            return true;
        }

        // Si le patient est admis et n'est pas dans leur service, l'accès est refusé.
    }

    // 4. Par défaut : Refusé
    return false;
}
    


    public function create(User $user): bool
    {
        return true; // Tous peuvent créer un patient
    }

    public function update(User $user, Patient $patient): bool
    {
        return $user->isAdmin() || $user->isAdministrative();
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->isAdmin();
    }

    public function viewMedicalFile(User $user, Patient $patient): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor() || $user->isNurse()) {
            return $this->view($user, $patient);
        }

        return false;
    }
}

  