<?php

namespace App\Observers;

use App\Actions\GenerateEmployeeNumber;
use App\Models\Employee;
use Illuminate\Support\Str;

class EmployeeObserver
{
    public function creating(Employee $employee): void
    {
        if (empty($employee->no_pegawai)) {
            $employee->no_pegawai = GenerateEmployeeNumber::run();
        }

        if (empty($employee->slug)) {
            $employee->slug = static::generateSlug($employee->nama_lengkap);
        }
    }

    protected static function generateSlug(string $name): string
    {
        $base = Str::slug($name).'-'.substr((string) Str::uuid(), 0, 8);

        return $base;
    }
}
