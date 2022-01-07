<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/01/2022 at 09:27
*/

use App\Controllers\Admin\ProductController;
use Core\Route;
use Core\Router;

Router::get((new Route([ProductController::class,'view']))->path('/store/product/view')->name('store.product.view'));
Router::get((new Route([ProductController::class,'list']))->path('/store/product/list')->name('store.product.list'));
