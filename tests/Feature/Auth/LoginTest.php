<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Filament\Pages\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * PR6 replaces the PR4 Blade login fallback (`/login`, `LoginController`,
 * `resources/views/auth/login.blade.php`) with Filament's own panel login
 * screen at `/admin/login`. This suite exercises that Filament login page
 * directly instead of the deleted Blade form/controller, but keeps the same
 * fixture-user convention as the original PR4 suite: a raw bcrypt hash
 * written directly via the query builder, exactly like `legacy:import`
 * (PR3) leaves a migrated user, at a cost factor (10) that intentionally
 * does NOT match this app's testing `BCRYPT_ROUNDS` (4) — proving login
 * authenticates against the imported hash exactly as stored.
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

    public function test_migrated_user_can_login_via_the_filament_panel_with_their_legacy_password_hash_unchanged(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'legacy-fixture@example.test',
                'password' => 'LegacyPlain123!',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $this->assertAuthenticated();
    }

    public function test_login_fails_with_incorrect_password(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'legacy-fixture@example.test',
                'password' => 'wrong-password',
            ])
            ->call('authenticate')
            ->assertHasFormErrors();

        $this->assertGuest();
    }

    public function test_unauthenticated_visitor_requesting_admin_is_redirected_to_the_filament_login_page(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_authenticated_user_can_access_the_admin_dashboard(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');
        $user = User::where('email', 'legacy-fixture@example.test')->firstOrFail();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }

    public function test_authenticated_user_can_logout_via_the_filament_panel(): void
    {
        $this->createLegacyStyleUser('legacy-fixture@example.test', 'LegacyPlain123!');
        $user = User::where('email', 'legacy-fixture@example.test')->firstOrFail();

        $response = $this->actingAs($user)->post(route('filament.admin.auth.logout'));

        $response->assertRedirect();
        $this->assertGuest();
    }
}
