<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * Exists ONLY so `artisan down`'s pre-boot fast path can see the
 * maintenance-mode exception list.
 *
 * When `MaintenanceToggler::activate()` calls `artisan down` with
 * `--render`, Laravel prerenders the 503 page and writes it into
 * `storage/framework/maintenance.php`'s stub, which short-circuits
 * `public/index.php` for every request BEFORE `vendor/autoload.php` or
 * `bootstrap/app.php` are even loaded. `DownCommand::excludedPaths()`
 * resolves its exception list at down-time by asking the container for
 * exactly this app-namespaced class (the pre-Laravel-11 convention) —
 * with no class here, resolution fails silently and falls back to `[]`.
 *
 * Note this list is declared directly here, NOT inherited from
 * `bootstrap/app.php`'s fluent `preventRequestsDuringMaintenance(except:
 * [...])` call: that call only runs when Laravel's HTTP Kernel is
 * resolved (`ApplicationBuilder::withMiddleware()` registers it via
 * `afterResolving(HttpKernel::class, ...)`), which never happens for a
 * console command like `artisan down` — confirmed empirically: without
 * this `$except` property, `/admin` still 503'd during a real `--render`
 * maintenance window even though `bootstrap/app.php` listed it, and only
 * `/up` worked (its exclusion is registered eagerly, unconditionally,
 * inside `withRouting()` itself). Keep this list in sync with
 * `bootstrap/app.php`'s exception list by hand.
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        'admin',
        'admin/*',
        'up',
    ];
}
