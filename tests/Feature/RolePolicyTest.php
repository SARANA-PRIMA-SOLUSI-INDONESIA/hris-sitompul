<?php

use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use App\Policies\EmployeePolicy;
use App\Policies\LeavePolicy;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super_admin']);
    Role::firstOrCreate(['name' => 'hr_admin']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'karyawan']);
});

it('assigns the employee role to newly created employees with email', function () {
    $employee = Employee::factory()->create([
        'email_pribadi' => 'test@example.com',
        'user_id' => null,
    ]);

    expect($employee->fresh()->user_id)->not->toBeNull();
    expect($employee->fresh()->user->hasRole('karyawan'))->toBeTrue();
});

it('scopes managers to their subordinates for employee access', function () {
    $manager = Employee::factory()->create();
    $subordinate = Employee::factory()->create(['atasan_id' => $manager->id]);
    $other = Employee::factory()->create();

    $managerUser = User::factory()->create(['name' => 'Manager']);
    $managerUser->assignRole('manager');
    $manager->update(['user_id' => $managerUser->id]);

    $policy = new EmployeePolicy;

    expect($policy->view($managerUser, $subordinate))->toBeTrue()
        ->and($policy->view($managerUser, $other))->toBeFalse();
});

it('allows employees to view only their own leave records', function () {
    $employee = Employee::factory()->create();
    $employeeUser = User::factory()->create();
    $employeeUser->assignRole('karyawan');
    $employee->update(['user_id' => $employeeUser->id]);

    $ownLeave = Leave::factory()->create(['employee_id' => $employee->id]);
    $otherLeave = Leave::factory()->create();

    $policy = new LeavePolicy;

    expect($policy->view($employeeUser, $ownLeave))->toBeTrue()
        ->and($policy->view($employeeUser, $otherLeave))->toBeFalse();
});

it('allows managers to approve leave of their subordinates', function () {
    $manager = Employee::factory()->create();
    $subordinate = Employee::factory()->create(['atasan_id' => $manager->id]);

    $managerUser = User::factory()->create();
    $managerUser->assignRole('manager');
    $manager->update(['user_id' => $managerUser->id]);

    $leave = Leave::factory()->create(['employee_id' => $subordinate->id]);

    $policy = new LeavePolicy;

    expect($policy->approve($managerUser, $leave))->toBeTrue();
});

it('prevents employees from approving their own leave', function () {
    $employee = Employee::factory()->create();
    $employeeUser = User::factory()->create();
    $employeeUser->assignRole('karyawan');
    $employee->update(['user_id' => $employeeUser->id]);

    $leave = Leave::factory()->create(['employee_id' => $employee->id]);

    $policy = new LeavePolicy;

    expect($policy->approve($employeeUser, $leave))->toBeFalse();
});
