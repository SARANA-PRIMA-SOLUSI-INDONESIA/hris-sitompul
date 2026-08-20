<?php

it('redirects the root path to the admin dashboard', function () {
    $this->get('/')
        ->assertRedirect(route('filament.admin.pages.dashboard'));
});

it('serves the admin login page', function () {
    $this->get('/admin/login')
        ->assertOk();
});

it('exposes the configured employee number prefix', function () {
    expect(config('app.employee_number_prefix'))->toBe('SIT');
});

it('uses Sitompul as the application brand name', function () {
    expect(config('app.name'))->toBe('SITOMPUL');
});
