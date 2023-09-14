<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentsTable extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'usigned'=>true,
				'constraint' => 5,
				'unsigned' => true,
				'auto_increment' => true
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => 200
			],
			'email' => [
				'type' => 'VARCHAR',
				'constraint' => 200
			],
			'date' => [
				'type' => 'VARCHAR',
				'constraint' => 20
			],
			'message' => [
				'type' => 'VARCHAR',
				'constraint' => 500
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('comments');
	}

	public function down()
	{
		$this->forge->dropTable('comments');
	}
}
