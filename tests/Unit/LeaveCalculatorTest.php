<?php

use App\Actions\GenerateEmployeeNumber;
use App\Actions\LeaveCalculator;
use Carbon\Carbon;

it('generates a unique employee number in the expected format', function () {
    $prefix = config('app.employee_number_prefix', 'SIT');

    $number = GenerateEmployeeNumber::run();

    expect($number)->toMatch('/^'.$prefix.'-\d{4}-\d{4}$/');
});
it('generates unique employee numbers', function () {
    $numbers = collect(range(1, 50))
        ->map(fn () => GenerateEmployeeNumber::run());

    expect($numbers->unique()->count())->toBe(50);
});

it('calculates working days excluding weekends', function () {
    $start = Carbon::parse('2026-08-03'); // Monday
    $end = Carbon::parse('2026-08-07');   // Friday

    expect(LeaveCalculator::countWorkingDays($start, $end))->toBe(5);
});

it('calculates zero working days for weekend-only range', function () {
    $start = Carbon::parse('2026-08-08'); // Saturday
    $end = Carbon::parse('2026-08-09');   // Sunday

    expect(LeaveCalculator::countWorkingDays($start, $end))->toBe(0);
});

it('returns zero days when end is before start', function () {
    expect(LeaveCalculator::countWorkingDays('2026-08-10', '2026-08-05'))->toBe(0);
});
