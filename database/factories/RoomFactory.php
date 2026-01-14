<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hospital_id' => \App\Models\Hospital::factory(),
            'room_number' => $this->faker->unique()->numberBetween(100, 999),
            'bed_capacity' => $this->faker->numberBetween(1, 4),
            'service_id' => \App\Models\Service::factory(),
            'status' => $this->faker->randomElement(['available', 'occupied', 'cleaning', 'maintenance']),
            'type' => $this->faker->randomElement(['standard', 'VIP', 'isolation']),
            'is_active' => true,
        ];
    }
}
