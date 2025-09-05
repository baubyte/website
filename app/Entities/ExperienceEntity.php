<?php

namespace App\Entities;

use DateTime;
use CodeIgniter\Entity\Entity;

class ExperienceEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	/**
	 * Cambia el formato de la fecha y lo setea
	 *
	 * @param string $start
	 * @return void
	 */
	public function setStart(string $start)
	{
		$start = str_replace('/', '-', $start);
		$start = (new DateTime($start))->format('Y-m-d');
		//Asignamos el atributo
		$this->attributes['start'] = $start;
	}
	/**
	 * Cambia el formato de la fecha y lo setea
	 *
	 * @param string $end
	 * @return void
	 */
	public function setEnd(string $end=NULL)
	{
		if (!empty($end)) {
			$end = str_replace('/', '-', $end);
			$end = (new DateTime($end))->format('Y-m-d');
		}else{
			$end = NULL;
		}
		//Asignamos el atributo
		$this->attributes['end'] = $end;
	}
	/**
	 * Formatea y setea el atributo company a todas las
	 * palabras a mayusculas
	 *
	 * @param string $company
	 * @return void
	 */
	public function setCompany(string $company)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$company = ucwords(strtolower($company));
		//Asignamos el atributo
		$this->attributes['company'] = $company;

	}
	/**
	 * Formatea y setea el atributo specialty_es a todas las
	 * palabras a mayusculas
	 *
	 * @param string $specialtyEs
	 * @return void
	 */
	public function setSpecialtyEs(string $specialtyEs)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$specialtyEs = ucwords(strtolower($specialtyEs));
		//Asignamos el atributo
		$this->attributes['specialty_es'] = $specialtyEs;

	}
	/**
	 * Formatea y setea el atributo specialty_en a todas las
	 * palabras a mayusculas
	 *
	 * @param string $specialtyEn
	 * @return void
	 */
	public function setSpecialtyEn(string $specialtyEn)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$specialtyEn = ucwords(strtolower($specialtyEn));
		//Asignamos el atributo
		$this->attributes['specialty_en'] = $specialtyEn;

	}
	/**
	 * Retorna la fecha start
	 * retorna Present
	 *
	 * @return void
	 */
	public function getStart()
	{
		$start = str_replace('-', '/', $this->attributes['start']);
		$start = (new DateTime($start))->format('d-m-Y');
		return $start;
	}
	/**
	 * Retorna la fecha end o en caso de que este vacio 
	 * retorna Present
	 *
	 * @return void
	 */
	public function getEnd()
	{
		/**
		 * Verificamos si esta vacio
		 */
		if (empty($this->attributes['end'])) {
			return lang('App.end_date');
		}

		$end = str_replace('-', '/', $this->attributes['end']);
		$end = (new DateTime($end))->format('d-m-Y');
		return $end;
	}
}
