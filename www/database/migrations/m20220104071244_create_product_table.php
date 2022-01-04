<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220103205429_create_product_table
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20220104071244_create_product_table
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'product';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->string('name', 32)->notNullable()
            ->text('description')->notNullable()
            ->int('category_id')->nullable()
            ->decimal('price')->notNullable()
            ->int('quantity')->notNullable()->default(0)
            ->foreign('category_id','category','id')->onDelete("SET NULL")->onUpdate('cascade')
            ->timestamp()
            ->endCreation();
	}
}

