<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// PR5/PR6 add the real admin routes (public content management, Filament
// resources). This group only wires up the `auth` guard boundary now, with
// a placeholder route so the middleware is exercised end-to-end today.
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return 'Admin placeholder — real admin routes land in PR5/PR6.';
    })->name('dashboard');
});
