<?php

namespace App\Http\Middleware;

use App\Support\Locale\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Syncs Laravel's own application locale (`app()->getLocale()`) with the
 * session-based locale (`App\Support\Locale\Locale::current()`), so
 * `resources/views/app.blade.php`'s `<html lang="...">` (and any future
 * `__()`/`trans()` usage) reflect the same value the rest of the app
 * resolves against.
 */
class SetApplicationLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale(Locale::current());

        return $next($request);
    }
}
