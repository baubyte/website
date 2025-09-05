<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
	/**
	 * Retorna la vista de inicio del
	 * panel de admin
	 *
	 * @return void
	 */
	public function index()
	{
		//Retornamos la vista principal
		return view('Admin/dashboard');
	}

	/**
	 * Crea un enlace simbolico
	 * Retorna success, en el caso de no poder crear
	 * usar el controlador render
	 * Ej: <?= base_url(route_to('image_profile',$profile->avatar))?>
	 *
	 * @return void
	 */
	public function set()
	{
		$targetFolder = WRITEPATH."uploads".DIRECTORY_SEPARATOR."profile".DIRECTORY_SEPARATOR."images";
		$linkFolder =  FCPATH."uploads".DIRECTORY_SEPARATOR."profile".DIRECTORY_SEPARATOR."images";
		//dd($targetFolder,$linkFolder);
		try {
			symlink($targetFolder,$linkFolder);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage(), $e->getCode(), $e);
		}
		return 'success';
		//ln -s /var/www/writable/uploads/profile/images /var/www/html/uploads/profile/images
	}
}
