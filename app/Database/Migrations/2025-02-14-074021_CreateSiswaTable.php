<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiswaTable extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'       => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
			'nama'     => ['type' => 'VARCHAR', 'constraint' => 100],
			'alamat'   => ['type' => 'TEXT'],
			'gender'   => ['type' => 'ENUM', 'constraint' => ['L', 'P']],
		]);
		$this->forge->addPrimaryKey('id');
		$this->forge->createTable('siswa');
	}

	public function down()
	{
		$this->forge->dropTable('siswa');
	}
}
