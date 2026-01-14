<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Admission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admission>
 */
class AdmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hospital_id' => 1,
            'patient_id' => 1,
            'room_id' => 1,
            'bed_id' => 1,
            'doctor_id' => 1,
            'admission_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'admission_type' => $this->faker->randomElement(['emergency', 'planned']),
            'status' => 'active',
            'alert_level' => $this->faker->randomElement(['stable', 'warning', 'critical']),
            'admission_reason' => $this->faker->sentence(),
        ];
    }
}
