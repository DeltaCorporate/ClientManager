<?php


use App\Controllers\Home\HomeController;
use Core\Router;

Router::get("/",[HomeController::class,'index'],'home');
Router::get("/test",[HomeController::class,'test'],'test');
Router::post("/",[HomeController::class,'index'],'home');

include_once '../routes/auth.php';

