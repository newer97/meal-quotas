<?php

namespace Database\Seeders;

use App\Models\MealServe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealServeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MealServe::factory()->count(500)->create();
    }
}
