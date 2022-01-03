<?php

namespace Database\migrations;

use Core\SqlBuilder;

class m20211222084023_create_password_reset_method
{
    private $tableName;
    private $table;

    public function __construct()
    {
        $this->tableName = 'passwordreset';
        $this->table = new SqlBuilder($this->tableName);
    }

    public function up(): string
    {
        $table = $this->table;
        return $table->create()
            ->id()
            ->int('user_id')->notNullable()
            ->string('token')->notNullable()
            ->timestamp()
            ->foreign('user_id', 'user', 'id')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ->endCreation();
    }
}