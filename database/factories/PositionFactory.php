<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->lexify('POS-????')),
            'nama' => fake()->unique()->jobTitle(),
            'level' => fake()->randomElement(['staff', 'senior_staff', 'supervisor', 'manager', 'direktur']),
            'department_id' => Department::factory(),
            'deskripsi' => fake()->sentence(),
            'aktif' => true,
        ];
    }
}
