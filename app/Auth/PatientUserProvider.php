<?php

namespace App\Auth;

use App\Models\Patient;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class PatientUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return;
        }

        $query = $this->newModelQuery();

        // Chercher par email OU par IPU
        $query->where(function ($q) use ($credentials) {
            $q->where('email', $credentials['email'] ?? $credentials['ipu'] ?? null)
              ->orWhere('ipu', $credentials['email'] ?? $credentials['ipu'] ?? null);
        });

        return $query->first();
    }


}
