<?php

namespace App\Controllers\Admin;

use App\Models\StudyModel;
use App\Entities\StudyEntity;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class StudyController extends BaseController
{
	protected $studyModel; //Modelo Study
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->studyModel = new StudyModel();
	}

	/**
	 * Vista principal de Gestion de Study
	 *
	 * @return view
	 */
	public function index()
	{
		//Retornamos la vista principal
		return view('Admin/Study/index');
	}

	/**
	 * Retorna los datos para el DataTable de Study
	 *
	 * @return json
	 */
	public function getDataTableStudy()
	{
		//Estado generado de la consulta
		$builder = $this->studyModel->getStudies();
		return DataTable::of($builder)
			//Agrega un columna con en este caso agrega los botones de editar y eliminar
			->add('action', function ($row) {
				return "
			<a class='btn btn-info btn-sm' href='" . site_url(route_to("show_study", $row->id)) . "'><i class='fas fa-eye'></i></a>
			<a class='btn btn-success btn-sm' href='" . site_url(route_to("edit_study", $row->id)) . "'><i class='fas fa-edit'></i></a> 
			<a type='submit' class='btn btn-danger btn-sm' href='" . site_url(route_to("delete_study", $row->id)) . "'><i class='fas fa-trash-alt'></i></a>
			";
			})
			//Agrega la columna para enumerar los registros
			->addNumbering('no')
			//Columnas habilitadas para la búsqueda
			->setSearchableColumns(['entity', 'title_es', 'start', 'end'])
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
		return view('Admin/Study/create');
	}

	/**
	 * Valida y da de alta un Study
	 * redirecciona a la vista index
	 *
	 * @return redirect
	 */
	public function store()
	{
		//Validamos lo datos
		if ($this->validate('studyStore') === false) {
			return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
		}
		/**Entidad Study asignamos las propiedades*/
		$studyEntity = new StudyEntity($this->request->getPost());
		if (!empty($this->request->getPost('end'))) {
			if (strtotime($studyEntity->end) < strtotime($studyEntity->start)) {
				return redirect()->back()->withInput()->with('errors', ['end' => "La Fecha Fin no puede ser Menor a la Fecha Inicio"]);
			}
		}
		/**Insertamos el Study */
		$this->studyModel->insert($studyEntity);
		return redirect()->route('study')->with('success', "Tu Estudio en {$studyEntity->entity} se Guardo Correctamente.");
	}
	/**
	 * Busca el Study por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function show(int $id)
	{
		//Buscamos el el Study por el id
		$study = $this->studyModel->find($id);
		if ($study == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Study/show', ['study' => $study]);
		}
	}
	/**
	 * Busca el Study por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function edit(int $id)
	{
		//Buscamos el el Study por el id
		$study = $this->studyModel->find($id);
		if ($study == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Study/edit', ['study' => $study]);
		}
	}
	/**
	 * Valida y da de edita un Study
	 * redirecciona a la vista index
	 *
	 * @return redirect
	 */
	public function update()
	{
		//Capturamos el id
		$id = $this->request->getPost('id');
		//Buscamos el el Experience por el id
		$study = $this->studyModel->find($id);
		if ($study == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		}
		//Validamos lo datos
		if ($this->validate('studyUpdate') === false) {
			return redirect()->route('edit_study', [$id])->withInput()->with('errors', $this->validator->getErrors());
		}
		/**Entidad Post asignamos las propiedades*/
		$studyEntity = new StudyEntity($this->request->getPost());
		if (!empty($this->request->getPost('end'))) {
			if (strtotime($studyEntity->end) < strtotime($studyEntity->start)) {
				return redirect()->back()->withInput()->with('errors', ['end' => "La Fecha Fin no puede ser Menor a la Fecha Inicio"]);
			}
		}
		/**Actualiza el Study */
		$this->studyModel->update($id, $studyEntity);
		return redirect()->route('study')->with('success', "Tu Estudio en {$studyEntity->entity} se Actualizó Correctamente.");
	}

	/**
	 * Busca el Study por le id
	 * y lo elimina
	 *
	 * @param int $id
	 * @return redirect
	 */
	public function delete(int $id)
	{
		//Buscamos el el Study por el id
		$study = $this->studyModel->find($id);
		if ($study == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			/**Actualiza el Study */
			$this->studyModel->delete($id);
			return redirect()->route('study')->with('success', "Tu Estudio en {$study->entity} se Elimino Correctamente.");
		}
	}
}
