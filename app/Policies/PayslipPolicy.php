<?php

namespace App\Policies;

use App\Models\Payslip;
use App\Models\User;

class PayslipPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin', 'manager']);
    }

    public function view(User $user, Payslip $payslip): bool
    {
        if ($user->hasAnyRole(['super_admin', 'hr_admin'])) {
            return true;
        }

        return $payslip->employee->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function update(User $user, Payslip $payslip): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function delete(User $user, Payslip $payslip): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }
}
