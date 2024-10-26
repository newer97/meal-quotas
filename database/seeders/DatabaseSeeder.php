<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $c = User::factory()->create([
            'name' => 'cashier',
            'email' => 'c@example.com',
        ]);
        $c->assignRole('cashier');

        $a = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);
        $a->assignRole('admin');

        $sa = User::factory()->create([
            'name' => 'superadmin',
            'email' => 'superadmin@example.com'
        ]);
        $sa->assignRole('superadmin');

        $this->call([
            StudentSeeder::class,
        ]);
    }
}
