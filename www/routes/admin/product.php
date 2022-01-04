<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/01/2022 at 09:27
*/

use App\Controllers\Admin\ProductController;
use Core\Route;
use Core\Router;

Router::get((new Route([ProductController::class,'view']))->path('/admin/product/view')->name('admin.product.view'));
