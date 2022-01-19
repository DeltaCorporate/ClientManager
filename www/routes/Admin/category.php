<?php

use App\Controllers\Admin\CategoryController;
use Core\Route;
use Core\Router;

;


Router::get((new Route([CategoryController::class,'list']))->path("/admin/category/list")->name("admin.category.list")->middleware("auth")->middleware('admin'));

Router::get((new Route([CategoryController::class,'createForm']))->path("/admin/category/create")->name("admin.category.create")->middleware("auth")->middleware('admin'));
Router::post((new Route([CategoryController::class,'create']))->path("/admin/category/create")->name("admin.category.create")->middleware("auth")->middleware('admin'));

Router::get((new Route([CategoryController::class,'updateForm']))->path("/admin/category/update")->name("admin.category.update")->middleware("auth")->middleware('admin'));
Router::post((new Route([CategoryController::class,'update']))->path("/admin/category/update")->name("admin.category.update")->middleware("auth")->middleware('admin'));

Router::post((new Route([CategoryController::class,'delete']))->path("/admin/category/delete")->name("admin.category.delete")->middleware("auth")->middleware('admin'));