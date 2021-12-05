<?php


use App\Controllers\Home\HomeController;
use Config\Router;

Router::get("/",[HomeController::class,'index'],'home');
Router::get("/test",[HomeController::class,'test'],'test');
Router::post("/",[HomeController::class,'index'],'home');
