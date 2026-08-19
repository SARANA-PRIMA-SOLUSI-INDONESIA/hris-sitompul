<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;

class LeavePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin', 'manager', 'karyawan']);
    }

    public function view(User $user, Leave $leave): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        if ($user->hasRole('manager')) {
            return $leave->employee->atasan_id === $user->employee?->id;
        }

        return $leave->employee->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin', 'karyawan']);
    }

    public function update(User $user, Leave $leave): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        return $leave->employee->user_id === $user->id
            && in_array($leave->status, [Leave::STATUS_DRAFT, Leave::STATUS_MENUNGGU]);
    }

    public function delete(User $user, Leave $leave): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin'])
            || ($leave->employee->user_id === $user->id && $leave->status === Leave::STATUS_DRAFT);
    }

    public function approve(User $user, Leave $leave): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        return $user->hasRole('manager') && $leave->employee->atasan_id === $user->employee?->id;
    }
}
