<?php


use Config\Application;

require "../vendor/autoload.php";
define("ROOT", dirname(__DIR__));
const ROOT_DIR = ROOT . "/public";



$app = new Application();

$app->run();

