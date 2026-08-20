<?php

use App\Filament\Auth\Pages\Register;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Filament\Panel;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('serves the admin registration page', function () {
    $this->get('/admin/register')
        ->assertOk()
        ->assertSee('Register');
});

it('creates an active user with the karyawan role', function () {
    Livewire::test(Register::class)
        ->set('data.name', 'Budi Sitompul')
        ->set('data.email', 'budi@example.com')
        ->set('data.password', 'secret-password-123')
        ->set('data.passwordConfirmation', 'secret-password-123')
        ->call('register')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'budi@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->is_active)->toBeTrue()
        ->and($user->hasRole('karyawan'))->toBeTrue();
});

it('hashes the new user password', function () {
    Livewire::test(Register::class)
        ->set('data.name', 'Sari Sitompul')
        ->set('data.email', 'sari@example.com')
        ->set('data.password', 'secret-password-123')
        ->set('data.passwordConfirmation', 'secret-password-123')
        ->call('register')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'sari@example.com')->first();

    expect(Hash::check('secret-password-123', $user->password))->toBeTrue();
});

it('allows the newly registered user to access the panel', function () {
    Livewire::test(Register::class)
        ->set('data.name', 'Dewi Sitompul')
        ->set('data.email', 'dewi@example.com')
        ->set('data.password', 'secret-password-123')
        ->set('data.passwordConfirmation', 'secret-password-123')
        ->call('register')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'dewi@example.com')->first();

    $panel = app(Panel::class);

    expect($user->canAccessPanel($panel))->toBeTrue();
});
