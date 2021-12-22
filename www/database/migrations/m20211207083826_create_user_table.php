<?php

namespace Database\migrations;

use Core\SqlBuilder;

class m20211207083826_create_user_table
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'user';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->string('username', 255)
            ->string('email', 32)
            ->string('password', 255)
            ->timestamp()
            ->endCreation();
	}
}