<?php

it('serves the public community homepage', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Marga Sitompul');
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
