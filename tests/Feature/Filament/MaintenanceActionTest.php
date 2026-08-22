<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\ManageProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Connects the `MaintenanceToggler` service (PR5) to a real panel Action on
 * the `ManageProfile` page. Every test that puts the app into maintenance
 * mode MUST bring it back up in `tearDown()` — see `MaintenanceModeTest`
 * for why the file-based driver's marker persists across tests otherwise.
 */
class MaintenanceActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    protected function tearDown(): void
    {
        Artisan::call('up');

        parent::tearDown();
    }

    public function test_the_action_activates_maintenance_mode(): void
    {
        $this->assertFalse(app()->isDownForMaintenance());

        Livewire::test(ManageProfile::class)
            ->callAction('toggleMaintenanceMode');

        $this->assertTrue(app()->isDownForMaintenance());
    }

    public function test_the_action_deactivates_maintenance_mode(): void
    {
        Artisan::call('down', [
            '--render' => 'errors::503',
            '--secret' => 'test-maintenance-secret',
        ]);

        $this->assertTrue(app()->isDownForMaintenance());

        Livewire::test(ManageProfile::class)
            ->callAction('toggleMaintenanceMode');

        $this->assertFalse(app()->isDownForMaintenance());
    }
}
