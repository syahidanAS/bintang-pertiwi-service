<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserProfile extends Migration
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
			  'uid' => 
				array(
				  'type' => 'INT',
				  'constraint' => '10',
				),
			  'full_name' => 
				array(
				  'type' => 'VARCHAR',
				  'constraint' => '50',
				),
				'address' => 
				array(
				  'type' => 'TEXT'
				),
				'phone' => 
				array(
				  'type' => 'VARCHAR',
				  'constraint' => '25',
				),
			  'created_at datetime default current_timestamp',
			  'updated_at datetime default current_timestamp on update current_timestamp',
			)
		  );
			$this->forge->addKey('id', true);
			$this->forge->addForeignKey('uid', 'users', 'id');
			$this->forge->createTable('profiles');
    }

    public function down()
    {
        $this->forge->dropTable('profiles');
    }
}
