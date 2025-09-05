<?php

namespace App\Controllers\Admin;

use App\Models\ProfileModel;
use App\Entities\ProfileEntity;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProfileController extends BaseController
{
	protected $profileModel;//Modelo Profile
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->profileModel = new ProfileModel();
	}

	/**
	 * Vista principal de Gestion de Profile
	 *
	 * @return view
	 */
	public function index()
	{
		//Retornamos la vista principal
		return view('Admin/Profile/index');
	}

	/**
	 * Retorna los datos para el DataTable de Profile
	 *
	 * @return json
	 */
	public function getDataTableProfile()
	{
		//Estado generado de la consulta
		$builder = $this->profileModel->getProfiles();
        return DataTable::of($builder)
		//Agrega un columna con en este caso agrega los botones de editar y eliminar
		->add('action', function($row){
            return "
			<a class='btn btn-info btn-sm' href='".site_url(route_to("show_profile",$row->id))."'><i class='fas fa-eye'></i></a>
			<a class='btn btn-success btn-sm' href='".site_url(route_to("edit_profile",$row->id))."'><i class='fas fa-edit'></i></a> 
			<a type='submit' class='btn btn-danger btn-sm' href='".site_url(route_to("delete_profile",$row->id))."'><i class='fas fa-trash-alt'></i></a>
			";
        })
		//Agrega la columna para enumerar los registros
		->addNumbering('no')
		//Columnas habilitadas para la búsqueda
		->setSearchableColumns(['name','surname'])
		//devuelve el JSON
		->toJson(true);
	}

	/**
	 * Retrona la vista para dar de alta
	 *
	 * @return view
	 */
	public function create()
	{
		//Retornamos la viste del formulario de alta
		return view('Admin/Profile/create');
	}

	/**
	 * Valida y da de alta un Profile
	 * redirecciona a la vista index
	 *
	 * @return redirect
	*/
	public function store()
	{
		//Validamos lo datos
		if ($this->validate('profileStore') === false) {
			return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
		}
		//Capturamos los archivos
		$avatar = $this->request->getFile('avatar');
		/**Entidad Post asignamos las propiedades*/
		$profileEntity = new ProfileEntity($this->request->getPost());
		//Generamos un nombre al azar y asignamos
		$profileEntity->avatar = $avatar->getRandomName();

		//Guardamos los archivos
		$pathAvatar = $avatar->store('profile/images/', $profileEntity->avatar);
		/**Insertamos el Profile */
		$this->profileModel->insert($profileEntity);
		return redirect()->route('profile')->with('success',"Tu Perfil {$profileEntity->fullName} se Guardo Correctamente.");
	}
	/**
	 * Busca el Profile por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function show(int $id)
	{
		//Buscamos el el Profile por el id
		$profile = $this->profileModel->find($id);
		if ($profile == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Profile/show',['profile' => $profile]);
		}
	}
	/**
	 * Busca el Profile por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function edit(int $id)
	{
		//Buscamos el el Profile por el id
		$profile = $this->profileModel->find($id);
		if ($profile == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Profile/edit',['profile' => $profile]);
		}
	}
	/**
	 * Valida y da de edita un Profile
	 * redirecciona a la vista index
	 *
	 * @return redirect
	*/
	public function update()
	{
		//Capturamos el id
		$id = $this->request->getPost('id');
		//Buscamos el el Profile por el id
		$profile = $this->profileModel->find($id);
		if ($profile == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		}
		//Validamos lo datos
		if ($this->validate('profileUpdate') === false) {
			return redirect()->route('edit_profile', [$id])->withInput()->with('errors', $this->validator->getErrors());
		}
		$oldAvatar = $this->request->getPost('oldAvatar');
		//Capturamos los archivos
		$avatar = $this->request->getFile('avatar');
		/**Entidad Post asignamos las propiedades*/
		$profileEntity = new ProfileEntity($this->request->getPost());
		if ($avatar->isValid()){
			//Generamos un nombre al azar y asignamos
			$profileEntity->avatar = $avatar->getRandomName();
			//Guardamos los archivos
			$pathAvatar = $avatar->store('profile/images/', $profileEntity->avatar);
			$pathOldAvatar = WRITEPATH."uploads".DIRECTORY_SEPARATOR."profile".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.$oldAvatar;
			//eliminamos el avatar anterior
			unlink($pathOldAvatar);
		}
		/**Actualiza el Profile */
		$this->profileModel->update($id,$profileEntity);
		return redirect()->route('profile')->with('success',"Tu Perfil {$profileEntity->fullName} se Actualizó Correctamente.");
	}

	/**
	 * Busca el Profile por le id
	 * y lo elimina
	 *
	 * @param int $id
	 * @return redirect
	 */
	public function delete(int $id)
	{
		//Buscamos el el Profile por el id
		$profile = $this->profileModel->find($id);
		if ($profile == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			/**Actualiza el Profile */
			$this->profileModel->delete($id);
			return redirect()->route('profile')->with('success',"Tu Perfil {$profile->fullName} se Elimino Correctamente.");
		}
	}
}
