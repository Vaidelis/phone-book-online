<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PhoneBookController;
use App\Http\Controllers\SharedPhoneBookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/phone-books', [PhoneBookController::class, 'index'])->name('phonebooks.index');
    Route::get('/phone-books/create', [PhoneBookController::class, 'create'])->name('phonebooks.create');
    Route::get('/phone-books/edit/{id}', [PhoneBookController::class, 'edit'])->name('phonebooks.edit');

    Route::get('/shared-phone-books', [SharedPhoneBookController::class, 'index'])->name('sharedPhonebooks.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
