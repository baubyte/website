<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_password_reset_request_page(): void
    {
        $response = $this->get('/admin/password-reset/request');

        $response->assertSuccessful();
    }

    public function test_authenticated_user_can_access_profile_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/profile');

        $response->assertSuccessful();
    }
}
