<?php

use App\Actions\GenerateEmployeeNumber;
use App\Models\Employee;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('generates a unique employee number in the expected format', function () {
    $prefix = config('app.employee_number_prefix', 'SIT');

    $number = GenerateEmployeeNumber::run();

    expect($number)->toMatch('/^'.$prefix.'-\d{4}-\d{4}$/');
});

it('generates unique employee numbers when creating employees', function () {
    $numbers = collect(range(1, 30))
        ->map(fn () => Employee::factory()->create()->no_pegawai);

    expect($numbers->unique()->count())->toBe(30);
});
