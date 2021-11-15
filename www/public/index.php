<?php

use App\Views\Views;
use Config\DotEnvParser;
use Config\Router;


require "../vendor/autoload.php";

/*$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__)."/config/","config.env");
$dotenv->load();*/

new DotEnvParser();
Views::dd($_SERVER);
$router = new Router();
$router->addRoutes();


Router::checkRoute();






