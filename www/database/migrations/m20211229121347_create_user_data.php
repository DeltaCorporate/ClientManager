<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20211229121347_create_user_data
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20211229121347_create_user_data
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'user_data';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
          //TODO: Add here the other columns;
            ->timestamp()
            ->endCreation();
	}
}

