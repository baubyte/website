<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSkills extends Migration
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
				'null' => false
			],
			'percentage'       => [
				'type'       => 'INT',
				'constraint' => 12,
				'null' => false
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
		$this->forge->createTable('skills');
	}

	public function down()
	{
		$this->forge->dropTable('skills');
	}
}
