<?php

namespace App\Controllers\Admin;

use App\Models\SkillModel;
use App\Entities\SkillEntity;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class SkillController extends BaseController
{
	protected $skillModel;//Modelo Skill
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->skillModel = new SkillModel();
	}
	/**
	 * Vista principal de Gestion de Skill
	 *
	 * @return view
	 */
	public function index()
	{
		//Retornamos la vista principal
		return view('Admin/Skill/index');
	}
	/**
	 * Retorna los datos para el DataTable de Skill
	 *
	 * @return json
	 */
	public function getDataTableSkill()
	{
		//Estado generado de la consulta
		$builder = $this->skillModel->getSkills();
        return DataTable::of($builder)
		//Agrega un columna con en este caso agrega los botones de editar y eliminar
		->add('action', function($row){
            return "
			<a class='btn btn-info btn-sm' href='".site_url(route_to("show_skill",$row->id))."'><i class='fas fa-eye'></i></a>
			<a class='btn btn-success btn-sm' href='".site_url(route_to("edit_skill",$row->id))."'><i class='fas fa-edit'></i></a> 
			<a type='submit' class='btn btn-danger btn-sm' href='".site_url(route_to("delete_skill",$row->id))."'><i class='fas fa-trash-alt'></i></a>
			";
        })
		//Agrega la columna para enumerar los registros
		->addNumbering('no')
		//Columnas habilitadas para la búsqueda
		->setSearchableColumns(['name','percentage'])
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
		return view('Admin/Skill/create');
	}
	/**
	 * Valida y da de alta un Skill
	 * redirecciona a la vista index
	 *
	 * @return redirect
	*/
	public function store()
	{
		//Validamos lo datos
		if ($this->validate('skillStore') === false) {
			return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
		}
		/**Entidad Post asignamos las propiedades*/
		$skillEntity = new SkillEntity($this->request->getPost());
		/**Insertamos el Skill */

		$this->skillModel->insert($skillEntity);
		return redirect()->route('skill')->with('success',"El Skill {$this->request->getPost('name')} se Guardo Correctamente.");;
	}
	/**
	 * Busca el Skill por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function show(int $id)
	{
		//Buscamos el el Skill por el id
		$skill = $this->skillModel->find($id);
		if ($skill == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Skill/show',['skill' => $skill]);
		}
	}
	/**
	 * Busca el Skill por le id
	 * y los pinta en la vista
	 *
	 * @param int $id
	 * @return view
	 */
	public function edit(int $id)
	{
		//Buscamos el el Skill por el id
		$skill = $this->skillModel->find($id);
		if ($skill== null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			//Retornamos la viste del formulario de alta
			return view('Admin/Skill/edit',['skill' => $skill]);
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
		$skill = $this->skillModel->find($id);
		if ($skill == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		}
		//Validamos lo datos
		if ($this->validate('skillUpdate') === false) {
			return redirect()->route('edit_skill', [$id])->withInput()->with('errors', $this->validator->getErrors());
		}
		/**Entidad Post asignamos las propiedades*/
		$skillEntity = new SkillEntity($this->request->getPost());
		/**Actualiza el skill */
		$this->skillModel->update($id,$skillEntity);
		return redirect()->route('skill')->with('success',"El Skill {$this->request->getPost('name')} se Actualizó Correctamente.");
	}
	/**
	 * Busca el Skill por le id
	 * y lo elimina
	 *
	 * @param int $id
	 * @return redirect
	 */
	public function delete(int $id)
	{
		//Buscamos el el Skill por el id
		$skill = $this->skillModel->find($id);
		if ($skill == null) {
			//si no la encuentra desencadenamos un excepción
			throw PageNotFoundException::forPageNotFound();
		} else {
			/**Actualiza el skill */
			$this->skillModel->delete($id);
			return redirect()->route('skill')->with('success',"El Skill $skill->name} se Elimino Correctamente.");
		}
	}
}
