<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MealServe>
 */
class MealServeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['successful', 'failed']);
        $failureReason = $status === 'failed' ? $this->faker->randomElement(['Student not active', 'Meal not served at this time', 'Meal already served']) : null;
        return [
            'meal_id' => \App\Models\Meal::inRandomOrder()->first()->id,
            'student_id' => \App\Models\Student::inRandomOrder()->first()->id,
            'status' => $status,
            'failure_reason' => $failureReason,
            'served_by' => \App\Models\User::inRandomOrder()->first()->id,
            'served_at' => $this->faker->dateTimeThisYear(),

        ];
    }
}
