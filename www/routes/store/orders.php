<?php


use App\Controllers\Store\OrderController;
use Core\Route;
use Core\Router;

Router::get((new Route([OrderController::class,"list"]))->name("store.orders.list")->path("/store/orders/list")->middleware("auth"));
Router::get((new Route([OrderController::class,"view"]))->name("store.orders.view")->path("/store/orders/view")->middleware("auth")->middleware("seeOrder"));
Router::get((new Route([OrderController::class,"download"]))->name("store.orders.download")->path("/store/orders/download")->middleware("auth")->middleware("seeOrder"));