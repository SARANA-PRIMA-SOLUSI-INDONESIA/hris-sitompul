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

it('creates simple employee records without HR master data', function () {
    $employee = Employee::factory()->create([
        'nama_lengkap' => 'Budi Sitompul',
        'nama_perusahaan' => 'PT Sitompul Bersama',
        'jabatan' => 'Anggota',
    ]);

    expect($employee->nama_lengkap)->toBe('Budi Sitompul')
        ->and($employee->nama_perusahaan)->toBe('PT Sitompul Bersama')
        ->and($employee->jabatan)->toBe('Anggota');
});
