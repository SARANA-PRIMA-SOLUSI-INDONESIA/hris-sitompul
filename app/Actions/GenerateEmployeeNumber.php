<?php

namespace App\Actions;

use App\Models\Employee;
use Illuminate\Support\Str;

class GenerateEmployeeNumber
{
    public static function run(?string $prefix = null): string
    {
        $prefix = $prefix ?: Str::upper((string) config('app.employee_number_prefix', 'SIT'));

        do {
            $number = sprintf('%s-%s-%04d', $prefix, now()->year, random_int(1, 9999));
        } while (Employee::withTrashed()->where('no_pegawai', $number)->exists());

        return $number;
    }
}
