<?php

namespace App\Actions;

use App\Models\Employee;
use Illuminate\Support\Str;

class GenerateEmployeeNumber
{
    public static function run(?string $prefix = null): string
    {
        $prefix = $prefix ?: Str::upper((string) config('app.employee_number_prefix', 'SIT'));
        $year = now()->year;

        $sequence = (int) Employee::withTrashed()
            ->where('no_pegawai', 'like', "{$prefix}-{$year}-%")
            ->count() + 1;

        do {
            $number = sprintf('%s-%s-%04d', $prefix, $year, $sequence++);
        } while (Employee::withTrashed()->where('no_pegawai', $number)->exists());

        return $number;
    }
}
