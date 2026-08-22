<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // LOAD-BEARING: without this exception list, `artisan down` locks
        // the site owner out of their own `/admin` panel with no way back
        // in except the CLI. `/up` stays excluded too so maintenance mode
        // can always be lifted via a health-check-triggered restart.
        //
        // This list is ALSO duplicated in
        // `app/Http/Middleware/PreventRequestsDuringMaintenance.php`:
        // when `MaintenanceToggler` calls `artisan down --render=...`,
        // Laravel prerenders the 503 page and every later request
        // short-circuits in `public/index.php` before this closure ever
        // runs (it's only registered once the HTTP Kernel resolves,
        // which console commands like `artisan down` never trigger). Keep
        // both lists identical.
        $middleware->preventRequestsDuringMaintenance(except: [
            'admin',
            'admin/*',
            'up',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
