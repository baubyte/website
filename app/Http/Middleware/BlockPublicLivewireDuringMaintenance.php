<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockPublicLivewireDuringMaintenance
{
    /**
     * Blocks unauthenticated users from making Livewire requests while the app is in maintenance mode.
     *
     * Since Livewire endpoints (like `/livewire/update`) are whitelisted from the global
     * PreventRequestsDuringMaintenance middleware (so the admin panel works), this middleware
     * acts as a secondary defense. It runs after the session is started and blocks any Livewire
     * request if the user is a guest during a maintenance window, preventing stale public
     * tabs from bypassing maintenance mode.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            app()->isDownForMaintenance()
            && $request->is('livewire*')
            && ! Auth::check()
        ) {
            abort(503, 'Service Unavailable');
        }

        return $next($request);
    }
}
