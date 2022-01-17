<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220115170317_create_review_table
*@NameSpace: Database\migrations;
*/
namespace Database\migrations;

use Core\SqlBuilder;

class m20220115170317_create_review_table
{
	private $tableName;
	private $table;

	public function __construct()
	{
		$this->tableName = 'review';
		$this->table = new SqlBuilder($this->tableName);
	}
	public function up(): string{
        return $this->table->create()
            ->id()
            ->int('user_id')->notNullable()
            ->int('testimonial_id')->notNullable()
            ->string("review")
            ->foreign("user_id","user","id")->onUpdate("cascade")->onDelete("cascade")
            ->foreign("testimonial_id","testimonial","id")->onUpdate("cascade")->onDelete("cascade")
            ->timestamp()
            ->endCreation();
	}
}

