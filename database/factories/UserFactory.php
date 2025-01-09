<?php

namespace Database\Factories;

use App\Models\Division;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $divisions = Division::all();

        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make(123),

            'nik' => fake()->nik(),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'gender' => Arr::random(['Laki - Laki', 'Perempuan']),
            'birth_date' => fake()->date(),
            'address' => fake()->address(),

            'division_id' => $divisions->random()->id,
            'role' => Arr::random(['Kepala Divisi', 'Anggota Divisi']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
