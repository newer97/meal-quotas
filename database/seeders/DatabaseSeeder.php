<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'cashier']);

        $sa = User::factory()->create([
            'name' => 'superadmin',
            'username' => 'naser'
        ]);
        $sa->assignRole('superadmin');

        $this->call([
            StudentSeeder::class,
            MealSeeder::class,
            MealServeSeeder::class,
        ]);
    }
}
