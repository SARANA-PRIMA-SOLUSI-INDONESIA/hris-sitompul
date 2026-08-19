<?php

namespace App\Policies;

use App\Models\EmployeeSalary;
use App\Models\User;

class EmployeeSalaryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin', 'manager']);
    }

    public function view(User $user, EmployeeSalary $salary): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        return $salary->employee->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function update(User $user, EmployeeSalary $salary): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function delete(User $user, EmployeeSalary $salary): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function restore(User $user, EmployeeSalary $salary): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, EmployeeSalary $salary): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
