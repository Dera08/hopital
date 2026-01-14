<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'ipu' => $this->faker->unique()->regexify('PAT-[0-9]{3}'),
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Homme', 'Femme', 'Other']),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'allergies' => $this->faker->optional()->sentence(),
            'medical_history' => $this->faker->optional()->paragraph(),
            'referring_doctor_id' => null,
            'hospital_id' => \App\Models\Hospital::factory(),
        ];
    }
}
