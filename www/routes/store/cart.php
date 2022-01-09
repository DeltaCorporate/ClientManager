<?php


use App\Controllers\Store\CartController;
use Core\Route;
use Core\Router;

Router::get((new Route([CartController::class,"view"]))->path("/store/cart/view")->name("store.cart.view"));
Router::post((new Route([CartController::class,"add"]))->path("/store/cart/add")->name("store.cart.add"));
Router::post((new Route([CartController::class,"remove"]))->path("/store/cart/remove")->name("store.cart.remove"));
