<?php

namespace App\Entities;

use DateTime;
use CodeIgniter\Entity\Entity;

class StudyEntity extends Entity
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
	public function setEnd(string $end="")
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
	 * Formatea y setea el atributo entity a todas las
	 * palabras a mayusculas
	 *
	 * @param string $entity
	 * @return void
	 */
	public function setEntity(string $entity)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$entity = ucwords(strtolower($entity));
		//Asignamos el atributo
		$this->attributes['entity'] = $entity;
	}
	/**
	 * Formatea y setea el atributo title_es a todas las
	 * palabras a mayusculas
	 *
	 * @param string $titleEs
	 * @return void
	 */
	public function setTitleEs(string $titleEs)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$titleEs = ucwords(strtolower($titleEs));
		//Asignamos el atributo
		$this->attributes['title_es'] = $titleEs;

	}
	/**
	 * Formatea y setea el atributo title_en a todas las
	 * palabras a mayusculas
	 *
	 * @param string $titleEn
	 * @return void
	 */
	public function setTitleEn(string $titleEn)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$titleEn = ucwords(strtolower($titleEn));
		//Asignamos el atributo
		$this->attributes['title_en'] = $titleEn;

	}
	/**
	 * Retorna la fecha start
	 * retorna Present
	 *
	 * @return void
	 */
	public function getStart()
	{
		$start = str_replace('/', '-', $this->attributes['start']);
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

		$end = str_replace('/', '-', $this->attributes['end']);
		$end = (new DateTime($end))->format('d-m-Y');
		return $end;
	}
}
