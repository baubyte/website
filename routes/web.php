<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PR6 replaces the PR4 Blade login fallback and the `admin.*` placeholder
// group entirely: the Filament panel (registered in
// `App\Providers\Filament\AdminPanelProvider`) now owns every `/admin/*`
// route — including its own login/logout screens and auth guard boundary —
// via `discoverResources()`/`discoverPages()` and the panel's `authMiddleware`.
