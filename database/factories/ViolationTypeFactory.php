<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ViolationType>
 */
class ViolationTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $types = [
            'Speeding', 'Beating Red Light', 'No Helmet', 'No Seatbelt',
            'Illegal Parking', 'Reckless Driving', 'Unregistered Vehicle',
            'Expired License', 'Overloading', 'Counterflow',
        ];

        return [
            'name'        => fake()->unique()->randomElement($types),
            'fine_amount' => fake()->randomElement([0, 500, 1000, 1500, 2000, 3000]),
            'description' => fake()->optional(0.5)->sentence(),
        ];
    }
}
