<?php

use App\Http\Controllers\EmployeeCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

Route::get('/card/{slug}', [EmployeeCardController::class, 'show'])
    ->name('card.show')
    ->where('slug', '[a-z0-9\-]+');
