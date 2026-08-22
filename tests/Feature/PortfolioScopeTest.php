<?php

namespace Tests\Feature;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Confirms the deliberately narrow scope of this work unit: the Portfolio
 * model/table exist, but neither a Filament Resource nor a public route are
 * wired up yet. The "Proyectos" section is deferred to a later PR.
 *
 * PR6 adds the second assertion below (no `PortfolioResource` registered on
 * the admin panel) alongside the original PR2 route check, so both the
 * public and admin scope exclusions are locked down together.
 */
class PortfolioScopeTest extends TestCase
{
    public function test_no_public_route_is_registered_for_portfolios(): void
    {
        $portfolioRoutes = collect(Route::getRoutes())
            ->filter(fn ($route) => str_contains($route->uri(), 'portfolio'));

        $this->assertCount(
            0,
            $portfolioRoutes,
            'Portfolio must not have any public route registered in this work unit.'
        );
    }

    public function test_no_portfolio_resource_is_registered_on_the_admin_panel(): void
    {
        $resourceClasses = collect(Filament::getPanel('admin')->getResources());

        $this->assertFalse(
            $resourceClasses->contains(fn ($resource) => str_contains($resource, 'Portfolio')),
            'No PortfolioResource may be registered on the admin panel in this work unit.'
        );
    }
}
