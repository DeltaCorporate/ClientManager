<?php


use App\Controllers\Store\PaymentController;
use Core\Route;
use Core\Router;

Router::post((new Route([PaymentController::class,"checkout"]))->name("store.checkout")->path("/store/payment/create")->middleware("auth"));
Router::get((new Route([PaymentController::class,"getPaymentStatus"]))->name("store.payment.status")->path("/store/payment/status")->middleware("auth"));
