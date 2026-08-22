<?php

namespace App\Http\Controllers;

use App\Support\Locale\Locale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * `GET /locale/{locale}` — session-based locale switch, replicating the
 * legacy CodeIgniter app's `LocaleController::set()`. No URL-prefixed
 * locales; the value is stored in session and the visitor is sent back to
 * where they came from.
 */
class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (! Locale::isSupported($locale)) {
            throw new BadRequestHttpException("[{$locale}] is not a supported locale.");
        }

        $request->session()->put('locale', $locale);

        return redirect()->back();
    }
}
