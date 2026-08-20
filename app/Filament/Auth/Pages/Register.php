<?php

namespace App\Filament\Auth\Pages;

use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Register extends BaseRegister
{
    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['is_active'] = true;

        return $data;
    }

    protected function handleRegistration(array $data): Model
    {
        $user = parent::handleRegistration($data);

        if (Role::where('name', 'karyawan')->exists()) {
            $user->assignRole('karyawan');
        }

        return $user;
    }
}
