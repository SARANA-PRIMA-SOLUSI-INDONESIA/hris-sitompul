<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveType>
 */
class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->lexify('LT-????')),
            'nama' => fake()->unique()->word().' Leave',
            'kuota_tahunan' => fake()->numberBetween(5, 30),
            'dibayar' => fake()->boolean(80),
            'maks_pengajuan' => fake()->numberBetween(3, 14),
            'aktif' => true,
        ];
    }
}
