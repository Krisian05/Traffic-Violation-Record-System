<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Violator>
 */
class ViolatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'     => fake()->firstName(),
            'last_name'      => fake()->lastName(),
            'middle_name'    => fake()->optional(0.6)->firstName(),
            'gender'         => fake()->randomElement(['Male', 'Female']),
            'contact_number' => fake()->optional(0.7)->numerify('09#########'),
            'license_number' => fake()->boolean(60) ? fake()->unique()->bothify('??-##-######') : null,
            'license_type'   => fake()->optional(0.5)->randomElement(['Non-Professional', 'Professional']),
        ];
    }
}
