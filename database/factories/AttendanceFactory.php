<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::all();
        $status = Arr::random(['hadir', 'izin', 'tidak hadir']);

        return [
            'absen_datang' => $status === 'hadir' ? fake()->dateTimeBetween('-8 hours', '-4 hours') : null,
            'absen_pulang' => $status === 'hadir' ? fake()->dateTimeBetween('-4 hours') : null,
            'alasan' => in_array($status, ['izin', 'tidak hadir']) ? fake()->sentence(3) : null,

            'status' => $status,
            'user_id' => $users->random()->id,
            // 'user_id' => 6,
            'created_at' => fake()->dateTimeBetween('-12 week', '+1 week'),
        ];
    }
}
