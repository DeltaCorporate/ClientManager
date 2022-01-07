<?php


use App\Controllers\Store\CartController;
use Core\Route;
use Core\Router;

Router::get((new Route([CartController::class,"view"]))->path("/store/cart/view")->name("store.cart.view"));
