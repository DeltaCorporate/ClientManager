<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 10:46
*@Class: SqlBuilder
*@NameSpace: Config
*/

namespace Core;

class SqlBuilder
{
    private $sql;
    private $tableName;


    public function __construct($tableName)
    {
        $this->sql = "";
        $this->tableName = $tableName;
    }

    public function create(): SqlBuilder
    {
        $this->sql = "CREATE TABLE IF NOT EXISTS `$this->tableName` (";
        return $this;
    }


    public function alter(): SqlBuilder
    {
        $this->sql = "ALTER TABLE `$this->tableName` ";
        return $this;
    }

    public function renameColumn(): SqlBuilder
    {
        $this->sql = "RENAME COLUMN `$this->tableName` ";
        return $this;
    }

    public function id(): SqlBuilder
    {
        $this->sql .= "id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function string(string $name, int $length = 255): SqlBuilder
    {
        $this->sql .= ",$name VARCHAR($length) NOT NULL";
        return $this;
    }

    public function text(string $name): SqlBuilder
    {
        $this->sql .= ",$name TEXT NOT NULL";
        return $this;
    }

    public function boolean(string $name): SqlBuilder
    {
        $this->sql .= ",$name TINYINT(1) NOT NULL";
        return $this;
    }

    public function int(string $name): SqlBuilder
    {
        $this->sql .= ",$name INT(11) NOT NULL";
        return $this;
    }

    public function integer(string $name): SqlBuilder
    {
        $this->sql .= ",$name INTEGER NOT NULL";
        return $this;
    }

    public function unsigned(): SqlBuilder
    {
        $this->sql .= " UNSIGNED";
        return $this;
    }

    public function unique(string $name): SqlBuilder
    {
        $this->sql .= ",UNIQUE KEY ($name),";
        return $this;
    }

    public function primary(string $name): SqlBuilder
    {
        $this->sql .= ",PRIMARY KEY ($name)";
        return $this;
    }

    public function dropColumn(string $name): SqlBuilder
    {
        $this->sql .= "DROP COLUMN $name,";
        return $this;
    }

    public function dropTable(): string
    {
        return $this->sql .= "DROP TABLE `$this->tableName`;";

    }

    public function foreign(string $name, string $table, string $column, string $constraintName = null): SqlBuilder
    {
        if ($constraintName == null) {
            $this->sql .= ",FOREIGN KEY ($name) REFERENCES $table($column)";
        } else {
            $this->sql .= "ADD CONSTRAINT `$constraintName` FOREIGN KEY (`$name`) REFERENCES `$table`(`$column`) ";
        }
        return $this;
    }

    public function onDelete(string $action = 'CASCADE'): SqlBuilder
    {
        $this->sql .= "ON DELETE $action ";
        return $this;
    }

    public function onUpdate(string $action = 'CASCADE'): SqlBuilder
    {
        $this->sql .= " ON UPDATE $action ";
        return $this;
    }


    public function decimal(string $name, int $length = 10, int $decimal = 2): SqlBuilder
    {
        $this->sql .= ",$name DECIMAL($length,$decimal) NOT NULL";
        return $this;
    }

    public function timestamp(): SqlBuilder
    {
        $this->sql .= ",created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
        $this->sql .= ",updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }


    public function endCreation(): string
    {
        $this->sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        return $this->sql;
    }

    public function end(): string
    {
        $this->sql .= ";";
        return $this->sql;
    }


}