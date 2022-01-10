<?php


use App\Controllers\Store\PaymentController;
use Core\Route;
use Core\Router;

Router::post((new Route([PaymentController::class,"checkout"]))->name("store.checkout")->path("/store/checkout")->middleware("auth"));
