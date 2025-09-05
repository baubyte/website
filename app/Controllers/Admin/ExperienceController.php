<?php

namespace App\Controllers\Admin;

use App\Models\ExperienceModel;
use App\Entities\ExperienceEntity;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class ExperienceController extends BaseController
{
	protected $experienceModel;//Modelo experience
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->experienceModel = new ExperienceModel();
	}

	/**
	 * Vista principal de Gestion de Experience
	 *
	 * @return view
	 */
	public function index()
	{
		//Retornamos la vista principal
		return view('Admin/Experience/index');
	}

	/**
	 * Retorna los datos para el DataTable de Experience
	 *
	 * @return json
	 */
	public function getDataTableExperience()
	{
		//Estado generado de la consulta
		$builder = $this->experienceModel->getExperiences();
        return DataTable::of($builder)
		//Agrega un columna con en este caso agrega los botones de editar y eliminar
		->add('action', function($row){
            return "
			<a class='btn btn-info btn-sm' href='".site_url(route_to("show_experience",$row->id))."'><i class='fas fa-eye'></i></a>
			<a class='btn btn-success btn-sm' href='".site_url(route_to("edit_experience",$row->id))."'><i class='fas fa-edit'></i></a> 
			<a type='submit' class='btn btn-danger btn-sm' href='".site_url(route_to("delete_experience",$row->id))."'><i class='fas fa-trash-alt'></i></a>
			";
        })
		//Agrega la columna para enumerar los registros
		->addNumbering('no')
		//Columnas habilitadas para la búsqueda
		->setSearchableColumns(['company','start','end'])
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
		return view('Admin/Experience/create');
	}

	/**
	 * Valida y da de alta un Experience
	 * redirecciona a la vista index
	 *
	 * @return redirect
	*/
	public function store()
	{
		//Validamos lo datos
		if ($this->validate('experienceStore') === false) {
			return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
		}
		/**Entidad Experience asignamos las propiedades*/
		$experienceEntity = new ExperienceEntity($this->request->getPost());
		if (!empty($this->request->getPost('end'))) {
			if (strtotime($experienceEntity->end) < strtotime($experienceEntity->start)) {
				return redirect()->back()->withInput()->with('errors',['end'=>"La Fecha Fin no puede ser Menor a la Fecha Inicio"] );
			}
		}
		/**Insertamos el experience */
		$this->experienceModel->insert($experienceEntity);
		return redirect()->route('experience')->with('success',"Tu Experiencia en {$experienceEntity->company} se Guardo Correctamente.");
	}
	/**
	 * Busca el Experience por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function show(int $id)
	{
		//Buscamos el el Experience por el id
		$experience = $this->experienceModel->find($id);
		if ($experience == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Experience/show',['experience' => $experience]);
		}
	}
	/**
	 * Busca el Experience por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function edit(int $id)
	{
		//Buscamos el el Experience por el id
		$experience = $this->experienceModel->find($id);
		if ($experience == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Experience/edit',['experience' => $experience]);
		}
	}
	/**
	 * Valida y da de edita un Experience
	 * redirecciona a la vista index
	 *
	 * @return redirect
	*/
	public function update()
	{
		//Capturamos el id
		$id = $this->request->getPost('id');
		//Buscamos el el Experience por el id
		$experience = $this->experienceModel->find($id);
		if ($experience == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		}
		//Validamos lo datos
		if ($this->validate('experienceUpdate') === false) {
			return redirect()->route('edit_experience', [$id])->withInput()->with('errors', $this->validator->getErrors());
		}
		/**Entidad Post asignamos las propiedades*/
		$experienceEntity = new ExperienceEntity($this->request->getPost());
		if (!empty($this->request->getPost('end'))) {
			if (strtotime($experienceEntity->end) < strtotime($experienceEntity->start)) {
				return redirect()->back()->withInput()->with('errors',['end'=>"La Fecha Fin no puede ser Menor a la Fecha Inicio"] );
			}
		}
		/**Actualiza el Experience */
		$this->experienceModel->update($id,$experienceEntity);
		return redirect()->route('experience')->with('success',"Tu Experiencia en {$experienceEntity->company} se Actualizó Correctamente.");
	}

	/**
	 * Busca el Experience por le id
	 * y lo elimina
	 *
	 * @param int $id
	 * @return redirect
	 */
	public function delete(int $id)
	{
		//Buscamos el el Experience por el id
		$experience = $this->experienceModel->find($id);
		if ($experience == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			/**Actualiza el Experience */
			$this->experienceModel->delete($id);
			return redirect()->route('experience')->with('success',"Tu Experiencia en {$experience->company} se Elimino Correctamente.");
		}
	}
}
