<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: m20220115151752_create_testimonials
*@NameSpace: Database\migrations;
*/

namespace Database\migrations;

use Core\SqlBuilder;

class m20220115151752_create_testimonials
{
    private $tableName;
    private $table;

    public function __construct()
    {
        $this->tableName = 'testimonials';
        $this->table = new SqlBuilder($this->tableName);
    }

    public function up(): string
    {
        return $this->table->create()
            ->id()
            ->int('user_id')
            ->int("product_id")
            ->text("comment")
            ->foreign('user_id','user',"id")->onUpdate('cascade')->onDelete('cascade')
            ->foreign('product_id','product',"id")->onUpdate('cascade')->onDelete('cascade')
            ->timestamp()
            ->endCreation();
    }
}

