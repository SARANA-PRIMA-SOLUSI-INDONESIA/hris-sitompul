<?php

namespace Database\Factories;

use App\Actions\GenerateEmployeeNumber;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'no_pegawai' => GenerateEmployeeNumber::run(),
            'user_id' => null,
            'nama_lengkap' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'agama' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'status_pernikahan' => fake()->randomElement(['lajang', 'menikah', 'cerai']),
            'alamat' => fake()->address(),
            'no_telp' => fake()->phoneNumber(),
            'email_pribadi' => fake()->unique()->safeEmail(),
            'foto' => null,
            'status_kepegawaian' => fake()->randomElement(['tetap', 'kontrak', 'magang']),
            'tanggal_bergabung' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'tanggal_kontrak_selesai' => null,
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'position_id' => Position::inRandomOrder()->first()?->id ?? Position::factory(),
            'atasan_id' => null,
            'tanggal_keluar' => null,
            'alasan_keluar' => null,
        ];
    }
}
