<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220104071244_create_category_table
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20220103205429_create_category_table
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'category';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->string('name')->notNullable()
            ->text('description')->notNullable()
            ->timestamp()
            ->endCreation();
	}
}
