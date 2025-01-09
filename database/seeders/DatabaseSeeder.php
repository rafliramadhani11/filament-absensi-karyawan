<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Division;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Division::factory()->create([
        //     'name' => 'Admin',
        //     'slug' => Str::slug('Admin'),
        // ]);

        // Division::factory()->create([
        //     'name' => 'Human Resource Development',
        //     'slug' => Str::slug('Human Resource Development'),
        // ]);

        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@mail.com',
        //     'is_admin' => true,
        //     'division_id' => 1,
        // ]);

        // Division::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'HRD',
        //     'email' => 'hrd@mail.com',
        //     'is_hrd' => true,
        //     'division_id' => 2,
        // ]);
        // User::factory(50)->create();
        Attendance::factory(50)->create();
    }
}
