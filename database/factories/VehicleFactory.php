<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'violator_id'  => \App\Models\Violator::factory(),
            'plate_number' => strtoupper(fake()->unique()->bothify('???-####')),
            'vehicle_type' => fake()->randomElement(['MV', 'MC']),
            'make'         => fake()->randomElement(['Toyota', 'Honda', 'Yamaha', 'Suzuki', 'Mitsubishi', 'Ford']),
            'model'        => fake()->word(),
            'color'        => fake()->colorName(),
            'year'         => fake()->optional(0.7)->numberBetween(2000, 2024),
        ];
    }
}
