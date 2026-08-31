<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        User::firstOrCreate(
            ['email' => 'admin@sitompul.or.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        )->assignRole('super_admin');

        User::updateOrCreate(
            ['email' => 'admin@sitompul.test'],
            [
                'name' => 'Super Admin Test',
                'password' => Hash::make('@Dm1n'),
                'is_active' => true,
            ]
        )->assignRole('super_admin');

        User::firstOrCreate(
            ['email' => 'hr@sitompul.or.id'],
            [
                'name' => 'HR Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        )->assignRole('hr_admin');

        $this->command?->info('Database seeded successfully (production).');
        $this->command?->warn('Login: admin@sitompul.test / @Dm1n');
    }
}
