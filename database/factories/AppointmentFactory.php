<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => \App\Models\Patient::factory(),
            'doctor_id' => \App\Models\User::factory()->create(['role' => 'doctor'])->id,
            'service_id' => \App\Models\Service::factory(),
            'appointment_datetime' => $this->faker->dateTimeBetween('now', '+1 month'),
            'duration' => $this->faker->numberBetween(15, 60),
            'status' => $this->faker->randomElement(['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show']),
            'type' => $this->faker->randomElement(['consultation', 'follow_up', 'emergency']),
            'is_recurring' => false,
            'recurrence_pattern' => null,
            'reason' => $this->faker->sentence(),
            'notes' => $this->faker->optional()->paragraph(),
            'reminder_sent' => false,
            'reminder_sent_at' => null,
            'hospital_id' => 1,
        ];
    }
}
