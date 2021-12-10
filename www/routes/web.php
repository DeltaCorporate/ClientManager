<?php


use App\Controllers\Home\HomeController;
use Core\Router;

Router::get("/",[HomeController::class,'index'],'home');
Router::get("/test",[HomeController::class,'test'],'test');
Router::post("/",[HomeController::class,'index'],'home');

Router::get("/template/emails/register-confirm",function(){
    return render('emails.confirm-password');
},"email");
