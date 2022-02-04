<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
		$this->forge->addField(
			array(
				'id' =>
				array(
					'type' => 'INT',
					'constraint' => '10',
					'auto_increment' => TRUE
				),
				'email' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '50',
				),
				'password' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '100',
				),
				'role' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '20',
				),
				'is_verified' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '20',
				),
				'created_at datetime default current_timestamp',
				'updated_at datetime default current_timestamp on update current_timestamp',
			)
		);

		$this->forge->addKey('id', true);
		$this->forge->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
