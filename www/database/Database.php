<?php

namespace Database;

use PDO;
use PDOException;

class Database
{
    static protected $instance = null;

    public static function connection($connectParams = [])
    {
        if (empty($connectParams)) {
            $host = $_SERVER['DB_HOST'];
            $dbname = $_SERVER['DB_NAME'];
            $username = $_SERVER['DB_USER'];
            $password = $_SERVER['DB_PASSWORD'];
        } else {
            $host = $connectParams['host'];
            $dbname = $connectParams['dbname'];
            $username = $connectParams['username'];
            $password = $connectParams['password'];
        }
        if (self::$instance == null) {
            try {

                self::$instance = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                self::$instance->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            } catch (PDOException $e) {
                if ($e->getCode() == "1049") {
                    $temp = new PDO("mysql:host=$host", $username, $password);
                    $temp->query("CREATE DATABASE $dbname");
                    $temp = null;
                    self::connection();
                } else {
                    dd($e->getMessage());
                }
            }
        }
    }

    /**
     * @return null
     */
    public static function getInstance()
    {
        return self::$instance;
    }

}