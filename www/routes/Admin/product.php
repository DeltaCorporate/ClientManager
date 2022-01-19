<?php

use App\Controllers\Admin\ProductController;
use Core\Route;
use Core\Router;

;


Router::get((new Route([ProductController::class,'list']))->path("/admin/product/list")->name("admin.product.list")->middleware("auth")->middleware('admin'));

Router::get((new Route([ProductController::class,'createForm']))->path("/admin/product/create")->name("admin.product.create")->middleware("auth")->middleware('admin'));
Router::post((new Route([ProductController::class,'create']))->path("/admin/product/create")->name("admin.product.create")->middleware("auth")->middleware('admin'));

Router::get((new Route([ProductController::class,'updateForm']))->path("/admin/product/update")->name("admin.product.update")->middleware("auth")->middleware('admin'));
Router::post((new Route([ProductController::class,'update']))->path("/admin/product/update")->name("admin.product.update")->middleware("auth")->middleware('admin'));

Router::post((new Route([ProductController::class,'delete']))->path("/admin/product/delete")->name("admin.product.delete")->middleware("auth")->middleware('admin'));