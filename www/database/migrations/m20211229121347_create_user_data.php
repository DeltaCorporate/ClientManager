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

    public function up(): string
    {
        return $this->table->create()
            ->id()
            ->int('user_id')->notNullable()
            ->string("firstname",32)->nullable()
            ->string('lastname',32)->nullable()
            ->string('telephone',16)->nullable()
            ->text("address")->nullable()
            ->longtext("roles")->nullable()//TODO:mettre a jour ceci dans le modele il ne faut pas que ce soit nullable
            ->string("avatar")->default("defaultAvatar.svg")
            ->timestamp()
            ->foreign('user_id', 'user', 'id')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ->endCreation();
    }
}

