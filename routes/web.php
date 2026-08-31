<?php

use App\Http\Controllers\EmployeeCardController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kabar/{slug}', function (string $slug) {
    $article = Article::query()->where('slug', $slug)->where('diterbitkan', true)->firstOrFail();

    return view('article.show', compact('article'));
})->name('article.show');

Route::get('/card/{slug}', [EmployeeCardController::class, 'show'])
    ->name('card.show')
    ->where('slug', '[a-z0-9\-]+');
