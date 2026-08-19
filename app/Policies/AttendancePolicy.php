<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin', 'manager']);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        if ($user->hasRole('manager')) {
            return $attendance->employee->atasan_id === $user->employee?->id;
        }

        return $attendance->employee->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }
}
