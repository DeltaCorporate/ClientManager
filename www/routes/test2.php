<?php


use App\Controllers\Test;
use Config\Router;

Router::get("/test2",[Test::class,'index2'],'test2');