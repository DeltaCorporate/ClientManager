<?php

use Config\DotEnvParser;
use Config\Router;



require "../vendor/autoload.php";
define("ROOT", dirname(__DIR__));
const ROOT_DIR = ROOT . "/public";
new DotEnvParser();
$router = new Router();







