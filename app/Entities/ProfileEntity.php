<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProfileEntity extends Entity
{
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	/**
	 * Mutator para obtener el nombre completo
	 *
	 * @return void
	 */
	public function getFullName()
	{
		return $this->name . ' ' . $this->surname;
	}
	/**
	 * Formatea y setea el atributo name a todas las
	 * palabras a mayusculas
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName(string $name)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$name = ucwords(strtolower($name));
		//Asignamos el atributo
		$this->attributes['name'] = $name;

	}
	/**
	 * Formatea y setea el atributo surname a todas las
	 * palabras a mayusculas
	 *
	 * @param string $surname
	 * @return void
	 */
	public function setSurname(string $surname)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$surname = ucwords(strtolower($surname));
		//Asignamos el atributo
		$this->attributes['surname'] = $surname;

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
	 * Formatea y setea el atributo language_es a todas las
	 * palabras a mayusculas
	 *
	 * @param string $languageEs
	 * @return void
	 */
	public function setLanguageEs(string $languageEs)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$languageEs = ucwords(strtolower($languageEs));
		//Asignamos el atributo
		$this->attributes['language_es'] = $languageEs;

	}
	/**
	 * Formatea y setea el atributo language_en a todas las
	 * palabras a mayusculas
	 *
	 * @param string $languageEn
	 * @return void
	 */
	public function setLanguageEn(string $languageEn)
	{
		//Hacemos que todas las palabras esten en mayusculas
		$languageEn = ucwords(strtolower($languageEn));
		//Asignamos el atributo
		$this->attributes['language_en'] = $languageEn;

	}
}
