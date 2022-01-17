<?php


use Core\Application;

require "../vendor/autoload.php";
define("ROOT", dirname(__DIR__));
const ROOT_DIR = ROOT . "/public";



$app = new Application();

try {
    $app->run();
} catch (\App\Exceptions\MiddlewareException $e) {
}

