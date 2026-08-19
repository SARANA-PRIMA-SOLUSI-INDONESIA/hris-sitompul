<?php

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Filament\Panel;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('allows active users with roles to access the panel', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('karyawan');

    $panel = app(Panel::class);

    expect($user->canAccessPanel($panel))->toBeTrue();
});

it('denies inactive users from accessing the panel', function () {
    $user = User::factory()->create(['is_active' => false]);
    $user->assignRole('karyawan');

    $panel = app(Panel::class);

    expect($user->canAccessPanel($panel))->toBeFalse();
});

it('denies users without roles from accessing the panel', function () {
    $user = User::factory()->create(['is_active' => true]);

    $panel = app(Panel::class);

    expect($user->canAccessPanel($panel))->toBeFalse();
});

it('hashes passwords correctly', function () {
    $user = User::factory()->create(['password' => 'secret123']);

    expect(Hash::check('secret123', $user->password))->toBeTrue();
});

it('exposes the admin login page without authentication', function () {
    $this->get('/admin/login')
        ->assertOk();
});

it('redirects unauthenticated users away from the dashboard', function () {
    $this->get('/admin')
        ->assertRedirect();
});
