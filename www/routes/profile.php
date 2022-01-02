<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 02/01/2022 at 10:33
*/


use App\Controllers\Authentification\ProfilController;
use Core\Route;
use Core\Router;

Router::get((new Route([ProfilController::class, "view"]))->name('user.profile')->path("/user/profile")->middleware("auth"));
Router::post((new Route([ProfilController::class, "update_avatar"]))->name("user.update_avatar")->path("/user/update_avatar")->middleware("auth"));