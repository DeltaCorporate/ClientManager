<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220113092612_create_order_table
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20220113092612_create_order_table
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'order';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->int("user_id")->notNullable()
            ->longtext("products")->notNullable()
            ->string("status")->notNullable()
            ->foreign("user_id","user","id")
                ->onUpdate("cascade")
                ->onDelete("cascade")
            ->timestamp()
            ->endCreation();
	}
}

