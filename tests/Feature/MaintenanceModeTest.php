<?php

namespace Tests\Feature;

use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Services\MaintenanceToggler;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Exercises Laravel's native maintenance mode (no custom `maintenance`
 * table — the framework's file-based driver, already configured for
 * testing via `phpunit.xml`'s `APP_MAINTENANCE_DRIVER=file`, handles
 * persistence on its own).
 *
 * `/admin/*` is excluded from `preventRequestsDuringMaintenance` in
 * `bootstrap/app.php` so the site owner is never locked out of their own
 * panel by `artisan down` with no way back in except the CLI. `/up` is
 * excluded too so the health-check route (and, in practice, `artisan up`
 * triggered from outside the app) keeps working while the site is down.
 *
 * These in-process tests all pass through Laravel's full HTTP kernel and
 * therefore CANNOT exercise `public/index.php`'s pre-boot maintenance
 * short-circuit (it reads a prerendered template straight off disk before
 * Composer's autoloader or `bootstrap/app.php` ever load). A real HTTP
 * smoke test against the running DDEV site during apply confirmed a real
 * gap this suite alone could not: `artisan down --render=...` (exactly
 * what `MaintenanceToggler` uses) resolves its own exception list from
 * `App\Http\Middleware\PreventRequestsDuringMaintenance::$except` — the
 * pre-Laravel-11 convention — NOT from `bootstrap/app.php`'s fluent
 * `preventRequestsDuringMaintenance(except: ...)`, which only registers
 * once the HTTP Kernel resolves and never runs for the `artisan down`
 * console command itself. Without that app-namespaced class, `/admin`
 * 503'd for real requests even though every test below passed. See that
 * class's docblock for the full explanation; `test_app_maintenance_middleware_declares_the_same_exception_list_as_bootstrap`
 * below is a regression lock against the two lists drifting apart again.
 *
 * Every test that puts the app into maintenance mode MUST bring it back
 * up in `tearDown()` — the file-based driver's `storage/framework/down`
 * marker persists across tests in the same process, so leaking it would
 * make every later test in the suite see a 503.
 */
class MaintenanceModeTest extends TestCase
{
    protected function tearDown(): void
    {
        Artisan::call('up');

        parent::tearDown();
    }

    public function test_public_route_returns_503_during_maintenance(): void
    {
        Artisan::call('down', [
            '--render' => 'errors::503',
            '--secret' => 'test-maintenance-secret',
        ]);

        $response = $this->get('/');

        $response->assertStatus(503);
    }

    public function test_admin_route_is_never_503_during_maintenance(): void
    {
        Artisan::call('down', [
            '--render' => 'errors::503',
            '--secret' => 'test-maintenance-secret',
        ]);

        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_up_route_is_never_503_during_maintenance(): void
    {
        Artisan::call('down', [
            '--render' => 'errors::503',
            '--secret' => 'test-maintenance-secret',
        ]);

        $response = $this->get('/up');

        $response->assertOk();
    }

    public function test_toggler_activates_and_deactivates_maintenance_mode(): void
    {
        $toggler = app(MaintenanceToggler::class);

        $this->assertFalse(app()->isDownForMaintenance());

        $toggler->activate();

        $this->assertTrue(app()->isDownForMaintenance());

        $toggler->deactivate();

        $this->assertFalse(app()->isDownForMaintenance());
    }

    public function test_app_maintenance_middleware_declares_the_same_exception_list_as_bootstrap(): void
    {
        $paths = (new PreventRequestsDuringMaintenance(app()))->getExcludedPaths();

        $this->assertContains('admin', $paths);
        $this->assertContains('admin/*', $paths);
        $this->assertContains('up', $paths);
    }
}
