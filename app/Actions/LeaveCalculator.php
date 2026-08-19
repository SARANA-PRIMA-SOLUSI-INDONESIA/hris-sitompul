<?php

namespace App\Actions;

use App\Models\Leave;
use App\Models\LeaveType;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class LeaveCalculator
{
    public static function countWorkingDays(CarbonInterface|string $start, CarbonInterface|string $end): int
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        if ($end->lt($start)) {
            return 0;
        }

        $days = 0;
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            if ($cursor->isWeekday()) {
                $days++;
            }
            $cursor->addDay();
        }

        return $days;
    }

    public static function remainingQuota(int $employeeId, int $leaveTypeId): int
    {
        $type = LeaveType::findOrFail($leaveTypeId);
        $year = now()->year;

        $used = Leave::query()
            ->where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->whereIn('status', [Leave::STATUS_DISETUJUI])
            ->whereYear('tanggal_mulai', $year)
            ->sum('jumlah_hari');

        return max(0, (int) $type->kuota_tahunan - (int) $used);
    }
}
