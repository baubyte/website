<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Middleware\EnsureSameOrigin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Session-based locale switch (PR9) — no URL-prefixed locales, replicates
// the legacy CodeIgniter app's `GET /locale/{locale}`. See
// `App\Http\Controllers\LocaleController` / `App\Support\Locale\Locale`.
Route::get('/locale/{locale}', LocaleController::class)->name('locale');

// CV PDF download (PR9), ported from the legacy `download_cv` route.
Route::get('/download-cv', [CvController::class, 'download'])->name('cv.download');

// Chat proxy (PR11, D8): a normal session-authenticated `web` route (NOT
// `routes/api.php`) on purpose, so Inertia's session/CSRF (`X-XSRF-TOKEN`)
// protection applies. `throttle:20,1` (spec value, supersedes the design
// doc's stale "10,1" reference per the tasks doc's reconciliation note) +
// `EnsureSameOrigin` sit in front of `ChatController`, which is the only
// thing that ever talks to the n8n webhook.
Route::post('/api/chat', ChatController::class)
    ->middleware(['throttle:20,1', EnsureSameOrigin::class])
    ->name('chat');

// PR6 replaces the PR4 Blade login fallback and the `admin.*` placeholder
// group entirely: the Filament panel (registered in
// `App\Providers\Filament\AdminPanelProvider`) now owns every `/admin/*`
// route — including its own login/logout screens and auth guard boundary —
// via `discoverResources()`/`discoverPages()` and the panel's `authMiddleware`.
