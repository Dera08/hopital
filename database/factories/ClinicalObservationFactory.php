<?php

namespace Database\Factories;

use App\Models\ClinicalObservation;
use App\Models\Patient;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicalObservationFactory extends Factory
{
    protected $model = ClinicalObservation::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'user_id' => User::factory(),
            'hospital_id' => Hospital::factory(),
            'type' => $this->faker->randomElement([
                'blood_pressure',
                'temperature',
                'heart_rate',
                'glucose'
            ]),
            'value' => $this->faker->randomFloat(2, 50, 200) . ' ' . $this->faker->randomElement(['mmHg', '°C', 'bpm', 'mg/dL']),
        ];
    }
}
