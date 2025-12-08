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
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDoctor() || $user->isNurse() || $user->isAdministrative()) {
            // Vérifier si le patient est dans leur service
            if ($user->service_id) {
                return $patient->admissions()
                    ->where('status', 'active')
                    ->whereHas('room', function($q) use ($user) {
                        $q->where('service_id', $user->service_id);
                    })
                    ->exists();
            }
        }

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

 