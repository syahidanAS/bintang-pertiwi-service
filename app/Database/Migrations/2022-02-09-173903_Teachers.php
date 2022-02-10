<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Teachers extends Migration
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
				'name' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '30',
				), 
				'major' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '20',
				),
				'campus' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '20',
				),
				'image_filename' =>
				array(
					'type' => 'TEXT'
				),
				'image_url' =>
				array(
					'type' => 'TEXT'
				),
				'created_at datetime default current_timestamp',
				'updated_at datetime default current_timestamp on update current_timestamp',
			)
		);
		$this->forge->addKey('id', true);
		$this->forge->createTable('teachers');
	}

	public function down()
	{
		//
	}
}
