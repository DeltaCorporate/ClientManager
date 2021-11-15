<?php


use App\Controllers\Test;
use Config\Router;

Router::post("/",[Test::class,'index2'],'test');