<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Meal::factory()->createMany([
            [
                'name' => 'Breakfast',
                'description' => 'Breakfast is the first meal of the day.',
                'start_time' => '03:00:00', // tines in utc
                'end_time' => '8:00:00',
            ],
            [
                'name' => 'Lunch',
                'description' => 'Lunch is the meal eaten in the middle of the day.',
                'start_time' => '8:00:00',
                'end_time' => '14:00:00',
            ],
            [
                'name' => 'Dinner',
                'description' => 'Dinner is the main meal of the day.',
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
            ],
        ]);
    }
}
