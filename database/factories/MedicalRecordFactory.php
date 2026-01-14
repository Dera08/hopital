<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'recorded_by_id' => User::factory(),
            'hospital_id' => Hospital::factory(),
            'content' => $this->faker->paragraph(),
        ];
    }
}
