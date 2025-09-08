<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Exception;

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
}
