<?php

namespace Database\Factories;

use App\Actions\LeaveCalculator;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Leave>
 */
class LeaveFactory extends Factory
{
    protected $model = Leave::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('this month', '+2 months')->format('Y-m-d');
        $end = date('Y-m-d', strtotime($start.' +'.random_int(1, 3).' weekdays'));

        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => LeaveType::factory(),
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
            'jumlah_hari' => LeaveCalculator::countWorkingDays($start, $end),
            'alasan' => fake()->sentence(),
            'lampiran' => null,
            'status' => fake()->randomElement([Leave::STATUS_MENUNGGU, Leave::STATUS_DISETUJUI, Leave::STATUS_DITOLAK]),
            'approved_by' => null,
            'approved_at' => null,
            'alasan_penolakan' => null,
        ];
    }
}
