<?php

namespace Database\Factories;

use App\Models\PatientVital;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientVitalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PatientVital::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'patient_name' => $this->faker->name,
            'patient_ipu' => 'PAT-' . $this->faker->unique()->numberBetween(100, 999),
            'temperature' => $this->faker->randomFloat(1, 36.0, 40.0),
            'pulse' => $this->faker->numberBetween(60, 100),
            'blood_pressure' => $this->faker->randomElement(['120/80', '130/85', '110/70']),
            'weight' => $this->faker->numberBetween(50, 100),
            'urgency' => $this->faker->randomElement(['normale', 'urgent', 'critique']),
            'reason' => $this->faker->sentence,
            'notes' => $this->faker->optional()->paragraph,
            'user_id' => User::factory(),
            'observations' => $this->faker->optional()->paragraph,
            'ordonnance' => $this->faker->optional()->paragraph,
            'status' => 'active',
        ];
    }
}
