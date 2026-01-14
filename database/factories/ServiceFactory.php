<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Dermatology']),
            'code' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'hospital_id' => \App\Models\Hospital::factory(),
        ];
    }
}
