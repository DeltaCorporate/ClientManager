<?php

use Config\Database;

class Table
{
    private $table;
    private $baseSql;

    public function __construct($table)
    {
        $this->table = $table;
        $this->baseSql = "CREATE TABLE IF NOT EXISTS `$table` (\n";
    }

    public function getTable()
    {
        return $this->table;
    }
    public function id(): Table
    {
        $this->baseSql .= " id INT AUTO_INCREMENT PRIMARY KEY NOT NULL\n";
        return $this;
    }
    public function string($name): Table
    {
         $this->baseSql .= "$name VARCHAR(255)\n";
        return $this;
    }
    public function int($name): Table
    {
        $this->baseSql .= "$name INT\n";
        return $this;
    }
    public function text($name): Table
    {
        $this->baseSql .= "$name TEXT\n";
        return $this;
    }
    public function date($name): Table
    {
        $this->baseSql .= "$name DATE\n";
        return $this;
    }
    public function double($name): Table
    {
        $this->baseSql .= "$name DOUBLE\n";
        return $this;
    }
    public function boolean($name): Table
    {
        $this->baseSql .= "$name BOOLEAN\n";
        return $this;
    }
    public function end(): Table
    {
         $this->baseSql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseSql(): string
    {
        return $this->baseSql;
    }

    public function create(){
        $sql = $this->getBaseSql();
        $db = new Database();
        $db->query($sql);
    }


}