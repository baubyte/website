<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExperiences extends Migration
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
			'company'       => [
				'type'       => 'VARCHAR',
				'constraint' => '120',
				'null' => false
			],
			'specialty_es'       => [
				'type'       => 'VARCHAR',
				'constraint' => '120',
				'null' => false
			],
			'specialty_en'       => [
				'type'       => 'VARCHAR',
				'constraint' => '120',
				'null' => false
			],
			'description_es'       => [
				'type'       => 'TEXT',
				'null' => true
			],
			'description_en'       => [
				'type'       => 'TEXT',
				'null' => true
			],
			'start' => [
				'type' => 'DATE',
				'null' => false,
			],
			'end' => [
				'type' => 'DATE',
				'null' => true,
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
		$this->forge->createTable('experiences');
	}

	public function down()
	{
		$this->forge->dropTable('experiences');
	}
}
