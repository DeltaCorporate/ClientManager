<?php

use App\Controllers\Admin\HomeController;
use Core\Route;
use Core\Router;

Router::get((new Route([HomeController::class, "index"]))->path("/admin")->name("admin.home")->middleware("auth")->middleware("admin"));