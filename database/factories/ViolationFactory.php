<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Violation>
 */
class ViolationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'violator_id'       => \App\Models\Violator::factory(),
            'violation_type_id' => \App\Models\ViolationType::factory(),
            'recorded_by'       => \App\Models\User::factory(),
            'date_of_violation' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'location'          => fake()->optional(0.8)->streetAddress(),
            'ticket_number'     => fake()->optional(0.7)->numerify('TKT-######'),
            'status'            => fake()->randomElement(['pending', 'settled', 'contested']),
        ];
    }
}
