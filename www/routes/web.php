<?php


use App\Controllers\Home\HomeController;
use Core\Route;
use Core\Router;

Router::get((new Route([HomeController::class, "index"]))->path("/")->name("home"));
Router::get((new Route([HomeController::class, "test"]))->path("/test")->name("test"));

include_once '../routes/auth.php';
include_once '../routes/profile.php';

