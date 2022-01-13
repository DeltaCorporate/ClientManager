<?php

use App\Controllers\Store\RemarkController;
use Core\Route;
use Core\Router;

Router::get((new Route([RemarkController::class,'list']))->path('/store/product/remark')->name('store.product.remark'));
