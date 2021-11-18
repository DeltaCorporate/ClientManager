<?php


use App\Controllers\Home\HomeController;
use Config\Router;

Router::get("/",[HomeController::class,'index'],'home');
