<?php

namespace App\Controllers\Admin\Settings;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class LocaleController extends BaseController
{
	/**
	 * Setea el idioma de la aplicacion
	 *
	 * @param string $locale
	 * @throws PageNotFoundException
	 * @return RedirectResponse
	 */
	public function set(string $locale)
	{	
		/**@var \Config\App */
		$configApp = config('App');
		//Verificamos que el lenguaje que se recibe este habilitada
		if (in_array($locale, $configApp->supportedLocales)) {
			//Recibimos y lo seteamos en la sesión
			session()->set('locale', $locale);
			//Seteamos el Lenguaje al sitio
			service('language')->setLocale($locale);
			cache()->save('locale', $locale, 3600);
			//Regresamos a la pagina de donde vino
			return redirect()->route("home");
		}else {
			//Lanzamos un a excepción
			throw PageNotFoundException::forPageNotFound(esc($locale).' Is not Supported Language');
		}
	}
}
