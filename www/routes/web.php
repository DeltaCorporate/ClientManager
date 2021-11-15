<?php


use App\Controllers\Test;
use Config\Router;

Router::get("/test",[Test::class,'index'],'test');
Router::get("/test/read",[Test::class,'index2'],'test2');