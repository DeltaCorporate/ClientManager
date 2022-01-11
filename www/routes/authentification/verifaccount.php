<?php

use App\Controllers\Authentification\AccountVerificationController;
use Core\Route;
use Core\Router;

Router::get((new Route([AccountVerificationController::class,"verify"]))->name("user.verifaccount")->path('/user/verifaacount')->middleware("guest"));