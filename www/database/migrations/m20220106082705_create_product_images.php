<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220106082705_create_product_images
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20220106082705_create_product_images
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'product_images';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->int('product_id')
            ->string('image')
            ->foreign("product_id","product","id")->onDelete('cascade')->onUpdate('cascade')
            ->timestamp()
            ->endCreation();
	}
}

