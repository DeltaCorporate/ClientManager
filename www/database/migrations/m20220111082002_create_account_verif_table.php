<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220111082002_create_account_verif_table
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20220111082002_create_account_verif_table
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'account_verif_token';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->string("token")->notNullable()
            ->int("user_id")->notNullable()
            ->foreign("user_id","user","id")
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ->timestamp()
            ->endCreation();
	}
}

