<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Images extends Migration
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
				'title' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '50',
				),
				'flag' =>
				array(
					'type' => 'VARCHAR',
					'constraint' => '20',
				),
				'path' =>
				array(
					'type' => 'TEXT'
				),
				'created_at datetime default current_timestamp',
				'updated_at datetime default current_timestamp on update current_timestamp',
			)
		);
		$this->forge->addKey('id', true);
		$this->forge->createTable('images');
    }

    public function down()
    {
        //
    }
}
