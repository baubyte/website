<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Exercises the native Laravel login/logout scaffold introduced in this
 * work unit. The fixture user is inserted the same way `legacy:import`
 * (PR3) leaves a migrated user: a raw bcrypt hash written directly via the
 * query builder, bypassing the `hashed` Eloquent cast entirely, at a cost
 * factor (10) that intentionally does NOT match this app's testing
 * `BCRYPT_ROUNDS` (4). This proves login authenticates against the
 * imported hash exactly as stored, with no forced reset/rehash gate —
 * `Hash::check()` (via `Auth::attempt()`) works across differing bcrypt
 * cost factors by design.
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function createLegacyStyleUser(string $email, string $plaintext): void
    {
        DB::table('users')->insert([
            'name' => 'Fixture Legacy User',
            'email' => $email,
            'password' => Hash::make($plaintext, ['rounds' => 10]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_migrated_user_can_login_with_their_legacy_password_hash_unchanged(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');

        $response = $this->post('/login', [
            'email' => 'legacy-fixture@example.test',
            'password' => 'LegacyPlain123!',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_incorrect_password(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');

        $response = $this->from('/login')->post('/login', [
            'email' => 'legacy-fixture@example.test',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_unauthenticated_request_to_admin_route_redirects_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_admin_route(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');
        $user = User::where('email', 'legacy-fixture@example.test')->firstOrFail();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');
        $user = User::where('email', 'legacy-fixture@example.test')->firstOrFail();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
