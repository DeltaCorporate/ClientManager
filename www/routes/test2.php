<?php


use App\Controllers\Test;
use Config\Router;

Router::get("/",[Test::class,'index2'],'test2');