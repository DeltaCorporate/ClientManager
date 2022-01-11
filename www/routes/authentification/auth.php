<?php


use App\Controllers\Authentification\AuthentificationController;
use Core\Route;
use Core\Router;

Router::get((new Route([AuthentificationController::class, "displayLoginForm"]))->name("user.login")->path("/user/login")->middleware("guest"));
Router::get((new Route([AuthentificationController::class, 'displayRegisterForm']))->name("user.register")->path("/user/register")->middleware("guest"));
Router::get((new Route([AuthentificationController::class, "displayForgotPasswordForm"]))->path("/user/forgot-password")->name("user.forgotpassword")->middleware("guest"));
Router::get((new Route([AuthentificationController::class, "displayResetPasswordForm"]))->name('user.displayresetpassword')->path("/user/display-reset-password")->middleware("guest"));
Router::get((new Route([AuthentificationController::class, "logout"]))->name("user.logout")->path("/user/logout"));


Router::post((new Route([AuthentificationController::class, "login"]))->name("user.login")->path("/user/login")->middleware("guest"));
Router::post((new Route([AuthentificationController::class, "register"]))->name("user.register")->path("/user/register")->middleware("guest"));
Router::post((new Route([AuthentificationController::class, "sendLinkToResetPassword"]))->name("user.forgotpassword")->path("/user/forgot-password")->middleware("guest"));
Router::get((new Route([AuthentificationController::class, "resetPassword"]))->name("user.resetpassword")->path("/user/reset-password")->middleware("guest"));
Router::post((new Route([AuthentificationController::class, "handleResetPassword"]))->name("user.handleresetpassword")->path("/user/handle-reset-password")->middleware("guest"));



