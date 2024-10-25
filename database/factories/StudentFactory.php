<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_number' => $this->faker->unique()->numerify('41#######'),
            'national_id' => $this->faker->unique()->numerify('10########'),
            'status' => $this->faker->randomElement(['active', 'graduated', 'suspended']),
            'name' => $this->faker->name(),

        ];
    }
}
