<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPortfolios extends Migration
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
			'company_name'       => [
				'type'       => 'VARCHAR',
				'constraint' => '120',
				'unique' => true,
				'null' => false
			],
			'image'       => [
				'type'       => 'VARCHAR',
				'constraint' => '40',
				'null' => false
			],
			'website_url_es'       => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'null' => true
			],
			'website_url_en'       => [
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
		$this->forge->createTable('portfolios');
	}

	public function down()
	{
		$this->forge->dropTable('portfolios');
	}
}
