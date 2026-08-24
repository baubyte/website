<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

/**
 * Thin, testable wrapper around Laravel's native maintenance mode.
 *
 * No `maintenance` table and no persistence of our own: the framework's
 * file-based driver (`APP_MAINTENANCE_DRIVER=file`) already tracks state
 * via a marker file under `storage/framework/`. This class only exists so
 * the Filament Action UI (`App\Filament\Pages\ManageProfile`) has a
 * single, simple, testable entry point instead of shelling out to
 * `artisan` directly.
 */
class MaintenanceToggler
{
    /**
     * Put the application into maintenance mode.
     *
     * Uses Laravel's default 503 view (`errors::503`, resolved from the
     * framework itself — no app-level override needed) and a random
     * `--secret` bypass token so the owner can still reach the site
     * through `/<secret>` if `/admin` were ever unreachable for another
     * reason.
     */
    public function activate(): void
    {
        Artisan::call('down', [
            '--render' => 'errors::503',
            '--secret' => Str::random(32),
        ]);
    }

    /**
     * Bring the application back up.
     */
    public function deactivate(): void
    {
        Artisan::call('up');
    }
}
