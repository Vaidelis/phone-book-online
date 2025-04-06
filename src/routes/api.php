<?php

use Illuminate\Support\Facades\Route;

Route::post('/tokens/create', [App\Http\Controllers\Api\TokenController::class, 'createToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/phone-books/store', [App\Http\Controllers\Api\PhoneBookController::class, 'store'])->name('phone-books.store');
    Route::put('/phone-books/update/{id}', [App\Http\Controllers\Api\PhoneBookController::class, 'update'])->name('phone-books.update');
    Route::post('/shared-phone-books/share/{id}', [App\Http\Controllers\Api\SharedPhoneBookController::class, 'share'])->name('shared-phone-books.share');
    Route::delete('/shared-phone-books/unshare/{id}', [App\Http\Controllers\Api\SharedPhoneBookController::class, 'unshare'])->name('shared-phone-books.unshare');
    Route::delete('/phone-books/delete/{id}', [App\Http\Controllers\Api\PhoneBookController::class, 'delete'])->name('phone-books.delete');
});

