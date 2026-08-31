<?php

namespace Database\Factories;

use App\Actions\GenerateEmployeeNumber;
use App\Models\Employee;
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
            'slug' => fake()->unique()->slug(),
            'tampilkan_kartu' => true,
            'nama_lengkap' => fake()->name(),
            'nama_perusahaan' => fake()->company(),
            'jabatan' => fake()->jobTitle(),
            'nik' => fake()->unique()->numerify('################'),
            'alamat' => fake()->address(),
            'no_telp' => '+628'.fake()->numerify('##########'),
            'email_pribadi' => fake()->unique()->safeEmail(),
            'foto' => null,
        ];
    }
}
