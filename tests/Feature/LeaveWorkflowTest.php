<?php

use App\Actions\ApproveLeave;
use App\Actions\LeaveCalculator;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('subtracts approved leaves from the annual quota', function () {
    $leaveType = LeaveType::factory()->create(['kuota_tahunan' => 12]);
    $employee = Employee::factory()->create();

    Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type_id' => $leaveType->id,
        'status' => Leave::STATUS_DISETUJUI,
        'jumlah_hari' => 3,
        'tanggal_mulai' => now()->addMonth()->startOfMonth(),
        'tanggal_selesai' => now()->addMonth()->startOfMonth()->addDays(2),
    ]);

    expect(LeaveCalculator::remainingQuota($employee->id, $leaveType->id))->toBe(9);
});

it('does not count pending leaves against the quota', function () {
    $leaveType = LeaveType::factory()->create(['kuota_tahunan' => 12]);
    $employee = Employee::factory()->create();

    Leave::factory()->create([
        'employee_id' => $employee->id,
        'leave_type_id' => $leaveType->id,
        'status' => Leave::STATUS_MENUNGGU,
        'jumlah_hari' => 5,
    ]);

    expect(LeaveCalculator::remainingQuota($employee->id, $leaveType->id))->toBe(12);
});

it('approves a pending leave', function () {
    $leave = Leave::factory()->create(['status' => Leave::STATUS_MENUNGGU]);
    $approver = User::factory()->create();

    $result = ApproveLeave::run($leave, $approver);

    expect($result->status)->toBe(Leave::STATUS_DISETUJUI)
        ->and($result->approved_by)->toBe($approver->id)
        ->and($result->approved_at)->not->toBeNull();
});

it('rejects a pending leave with a reason', function () {
    $leave = Leave::factory()->create(['status' => Leave::STATUS_MENUNGGU]);
    $approver = User::factory()->create();

    $result = ApproveLeave::reject($leave, $approver, 'Kuota tidak mencukupi');

    expect($result->status)->toBe(Leave::STATUS_DITOLAK)
        ->and($result->alasan_penolakan)->toBe('Kuota tidak mencukupi');
});

it('cannot approve an already approved leave', function () {
    $leave = Leave::factory()->create(['status' => Leave::STATUS_DISETUJUI]);
    $approver = User::factory()->create();

    ApproveLeave::run($leave, $approver);
})->throws(RuntimeException::class);
