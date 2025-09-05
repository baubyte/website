<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Config\Services;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class InitSeeder extends Seeder
{
	/**
	 * @var Authorize
	 */
	protected $authorize;

	/**
	 * @var Db
	 */
	protected $db;

	/**
	 * @var Users
	 */
	protected $users;

	public function __construct()
	{
		$this->authorize = Services::authorization();
		$this->db = \Config\Database::connect();
		$this->users = new UserModel();
	}

	public function run()
	{
        // Usuario
        $this->users->save(new User([
            'email'    => 'admin@baubyte.com.ar',
            'username' => 'baubyte',
            'password' => 'super-baubyte',
            'active'   => '1',
        ]));
        // Roles
        $this->authorize->createGroup('admin', 'Administradores. La parte superior de la cadena alimentaria.');

		// Permisos
		$this->authorize->createPermission('admin-website', 'El usuario puede acceder al panel de administración.');
		$this->authorize->createPermission('gestionar-usuarios', 'El usuario puede crear, eliminar o modificar a los usuarios.');
		$this->authorize->createPermission('roles-permisos', 'El usuario puede editar y definir permisos para un rol.');

		// Asignando permisos a los roles
		$this->authorize->addPermissionToGroup('admin-website', 'admin');
		$this->authorize->addPermissionToGroup('gestionar-usuarios', 'admin');
		$this->authorize->addPermissionToGroup('roles-permisos', 'admin');

		// Asignando Roles al usuario
		$this->authorize->addUserToGroup(1, 'admin');

		// Asignando Permisos al usuario
		$this->authorize->addPermissionToUser('admin-website', 1);
		$this->authorize->addPermissionToUser('gestionar-usuarios', 1);
		$this->authorize->addPermissionToUser('roles-permisos', 1);

	}
}
