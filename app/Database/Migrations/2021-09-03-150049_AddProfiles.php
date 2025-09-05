<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeams extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 12,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'name'       => [
				'type'       => 'VARCHAR',
				'constraint' => '120',
				'unique' => true,
				'null' => false
			],
			'surname'       => [
				'type'       => 'VARCHAR',
				'constraint' => '120',
				'unique' => true,
				'null' => false
			],
			'avatar'       => [
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null' => false
			],
			'email_contact'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'description_es'       => [
				'type'       => 'TEXT',
				'null' => true
			],
			'description_en'       => [
				'type'       => 'TEXT',
				'null' => true
			],
			'specialty_es'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => false
			],
			'specialty_en'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'language_es'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'language_en'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'github_url'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'linkedin_url'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'instagram_url'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => false,
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
			'deleted_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('profiles');
	}

	public function down()
	{
		$this->forge->dropTable('profiles');
	}
}
