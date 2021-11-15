<?php


use App\Controllers\Test;
use Config\Router;

Router::get("/test",[Test::class,'index'],'test');

include_once "../routes/test2.php";