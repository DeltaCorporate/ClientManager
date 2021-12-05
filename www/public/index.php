<?php

use Config\DotEnvParser;
use Config\Router;
use Database\Database;


require "../vendor/autoload.php";
define("ROOT", dirname(__DIR__));
const ROOT_DIR = ROOT . "/public";
new DotEnvParser();
Database::connection();
$router = new Router();






