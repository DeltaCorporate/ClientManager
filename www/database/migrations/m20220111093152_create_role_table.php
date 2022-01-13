<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220111093152_create_role_table
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20220111093152_create_role_table
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'role';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->string("name")->notNullable()
            ->text("description")->notNullable()
            ->timestamp()
            ->endCreation();
	}
}

