<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Confirms the deliberately narrow scope of this work unit: the Portfolio
 * model/table exist, but neither a Filament Resource nor a public route are
 * wired up yet. The "Proyectos" section is deferred to a later PR.
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
}
