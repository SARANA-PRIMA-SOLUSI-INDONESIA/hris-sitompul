<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin', 'manager']);
    }

    public function view(User $user, Employee $employee): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        if ($user->hasRole('manager')) {
            return $user->employee?->id === $employee->id
                || $user->employee?->bawahan()->whereKey($employee->id)->exists();
        }

        return $user->employee?->id === $employee->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function update(User $user, Employee $employee): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        return $user->employee?->id === $employee->id;
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function restore(User $user, Employee $employee): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, Employee $employee): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
