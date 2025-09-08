<?php

namespace App\Controllers\Admin\Settings;

use Exception;
use App\Controllers\BaseController;
use App\Models\Settings\MaintenanceModel;

class SettingsController extends BaseController
{

	public function index()
	{
		$status = isModeMaintenance();
		return view('Admin/Config/settings',compact('status'));
	}
	/**
	 * Crea un enlace simbolico
	 * Retorna success, en el caso de no poder crear
	 * usar el controlador render
	 * Ej: <?= base_url(route_to('image_profile',$profile->avatar))?>
	 *
	 * @throws \Exception
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function set()
	{
		try {
			$targetFolder = WRITEPATH . "uploads";
			$linkFolder = FCPATH . "uploads";
			$status = 'success';
			$message = 'Enlace simbólico creado correctamente en Windows.';
			if (is_link($linkFolder) || file_exists($linkFolder)) {
				return redirect()->back()->with('info', "El enlace simbólico ya existe o el directorio destino ya está en uso.");
			}
			if (PHP_OS_FAMILY === 'Windows') {
				$mode 		= is_dir($targetFolder) ? 'J' : 'H';
				$command 	= "mklink /{$mode} " . escapeshellarg($linkFolder) . " " . escapeshellarg($targetFolder);
				exec($command, $output, $returnVar);
				$status = $returnVar === 0 ? 'success' : 'error';
			}
			if (PHP_OS_FAMILY !== 'Windows') {
				$returnVar 	= symlink($targetFolder, $linkFolder);
				$status 	= $returnVar ? 'success' : 'error';
			}
			if ($status === 'success') {
				return redirect()->back()->with('success', "Enlace simbólico creado correctamente.");
			} else {
				return redirect()->back()->with('error', "Error al crear el enlace simbólico.");
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			return redirect()->back()->with('error', "Ocurrió un error al crear el enlace simbólico.");
		}
	}

	/**
	 * Limpiar Cache del sitio
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function cache_clean_site()
	{
		cache()->clean();
		return redirect()->back()->with('success', 'Cache del sitio se limpió correctamente.');
	}

	/**
	 * Actualizar el estado del mantenimiento
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function maintenance_update(string $status)
	{
		$maintenanceModel = new MaintenanceModel();
		$maintenanceModel->updateMaintenanceStatus(filter_var($status, FILTER_VALIDATE_BOOLEAN));
		return redirect()->back()->with('success', 'Estado de mantenimiento actualizado correctamente.');
	}
}
