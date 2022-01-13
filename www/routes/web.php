<?php


use App\Controllers\Home\HomeController;
use Core\Route;
use Core\Router;

Router::get((new Route([HomeController::class, "index"]))->path("/")->name("home"));
Router::get((new Route([HomeController::class, "test"]))->path("/test")->name("test"));

include_once '../routes/authentification/auth.php';
include_once '../routes/authentification/profile.php';
include_once '../routes/authentification/verifaccount.php';
include_once '../routes/store/product.php';
include_once "../routes/store/cart.php";
include_once  "../routes/store/payment.php";
include_once  "../routes/store/orders.php";

