<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement([
                Attendance::STATUS_HADIR,
                Attendance::STATUS_IZIN,
                Attendance::STATUS_SAKIT,
                Attendance::STATUS_CUTI,
                Attendance::STATUS_ALPHA,
            ]),
            'jam_masuk' => fake()->optional(0.9)->time('H:i'),
            'jam_keluar' => fake()->optional(0.9)->time('H:i'),
            'keterangan' => null,
        ];
    }
}
