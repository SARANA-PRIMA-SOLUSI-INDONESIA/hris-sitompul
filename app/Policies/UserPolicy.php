<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restoreAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
