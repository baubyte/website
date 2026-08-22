<?php

use App\Http\Controllers\CvController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Session-based locale switch (PR9) — no URL-prefixed locales, replicates
// the legacy CodeIgniter app's `GET /locale/{locale}`. See
// `App\Http\Controllers\LocaleController` / `App\Support\Locale\Locale`.
Route::get('/locale/{locale}', LocaleController::class)->name('locale');

// CV PDF download (PR9), ported from the legacy `download_cv` route.
Route::get('/download-cv', [CvController::class, 'download'])->name('cv.download');

// PR6 replaces the PR4 Blade login fallback and the `admin.*` placeholder
// group entirely: the Filament panel (registered in
// `App\Providers\Filament\AdminPanelProvider`) now owns every `/admin/*`
// route — including its own login/logout screens and auth guard boundary —
// via `discoverResources()`/`discoverPages()` and the panel's `authMiddleware`.
