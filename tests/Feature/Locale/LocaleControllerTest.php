<?php

namespace Tests\Feature\Locale;

use Tests\TestCase;

/**
 * Session-based locale switch, replicating the legacy CodeIgniter app's
 * `GET /locale/{locale}` (`app/Controllers/LocaleController.php`) — no
 * URL-prefixed locales (`/es/`, `/en/`), just a session write + redirect
 * back. See `App\Http\Controllers\LocaleController`.
 */
class LocaleControllerTest extends TestCase
{
    public function test_switching_to_a_supported_locale_stores_it_in_session_and_redirects_back(): void
    {
        $response = $this->from('/some-previous-page')->get('/locale/en');

        $response->assertRedirect('/some-previous-page');
        $this->assertSame('en', session('locale'));
    }

    public function test_switching_back_to_the_default_spanish_locale_stores_it_in_session(): void
    {
        $response = $this->from('/')->get('/locale/es');

        $response->assertRedirect('/');
        $this->assertSame('es', session('locale'));
    }

    public function test_switching_to_an_unsupported_locale_responds_with_400_and_does_not_touch_session(): void
    {
        $response = $this->get('/locale/fr');

        $response->assertStatus(400);
        $this->assertNull(session('locale'));
    }
}
