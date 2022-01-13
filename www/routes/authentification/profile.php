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
Router::post((new Route([ProfilController::class, "update_account_settings"]))->name("user.update_account_settings")->path("/user/update_account_settings")->middleware("auth"));
Router::post((new Route([ProfilController::class, "update_account_details"]))->name("user.update_account_details")->path("/user/update_account_details")->middleware("auth"));
Router::post((new Route([ProfilController::class, "update_password"]))->name("user.update_password")->path("/user/update_password")->middleware("auth"));
Router::post((new Route([ProfilController::class, "delete_account"]))->name("user.delete_account")->path("/user/delete_account")->middleware("auth"));