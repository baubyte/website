<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class LocaleController extends BaseController
{
	/**
	 * Setea el idioma de la aplicacion
	 *
	 * @param string $locale
	 * @return void
	 */
	public function set(string $locale)
	{
		//Verificamos que el lenguaje que se recibe este habilitada
		if (in_array($locale, config('App')->supportedLocales)) {
			//Recibimos y lo seteamos en la sesión
			session()->set('locale', $locale);
			//Regresamos a la pagina de donde vino
			return redirect()->back();
		}else {
			//Lanzamos un a excepción
			throw PageNotFoundException::forPageNotFound(esc($locale).' Is not Supported Language');
		}
	}
}
