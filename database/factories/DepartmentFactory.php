<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->lexify('DEPT-????')),
            'nama' => fake()->unique()->jobTitle().' Department',
            'parent_id' => null,
            'kepala_id' => null,
            'deskripsi' => fake()->sentence(),
            'aktif' => true,
        ];
    }
}
