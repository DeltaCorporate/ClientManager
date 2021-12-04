<?php

namespace Database;

use PDO;
use PDOException;

class Database
{
    public function __construct()
    {
        $host = $_SERVER['DB_HOST'];
        $dbname = $_SERVER['DB_NAME'];
        $username = $_SERVER['DB_USER'];
        $password = $_SERVER['DB_PASS'];
        try {
            $database = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $database->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
        } catch (PDOException $e) {
            dd($e->getMessage());
        }
        return $database;
    }

}