<?php

namespace App\Policies;

use App\Models\User;

trait AdminOnlyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function view(User $user, mixed $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function update(User $user, mixed $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function restore(User $user, mixed $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return $user->hasRole('super_admin');
    }
}
