<?php

namespace App\Observers;

use App\Actions\GenerateEmployeeNumber;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Str;

class EmployeeObserver
{
    public function creating(Employee $employee): void
    {
        if (empty($employee->no_pegawai)) {
            $employee->no_pegawai = GenerateEmployeeNumber::run();
        }
    }

    public function created(Employee $employee): void
    {
        if ($employee->user_id === null && $employee->email_pribadi) {
            $user = User::create([
                'name' => $employee->nama_lengkap,
                'email' => $employee->email_pribadi,
                'password' => Str::password(16),
                'is_active' => true,
            ]);
            $user->assignRole('karyawan');

            $employee->user_id = $user->id;
            $employee->saveQuietly();
        }
    }
}
