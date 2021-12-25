<?php


use App\Controllers\Authentification\AuthentificationController;
use Core\Route;
use Core\Router;

Router::get((new Route([AuthentificationController::class, "displayLoginForm"]))->name("user.login")->path("/user/login"));
Router::get((new Route([AuthentificationController::class, 'displayRegisterForm']))->name("user.register")->path("/user/register"));
Router::get((new Route([AuthentificationController::class, "displayForgotPasswordForm"]))->path("/user/forgot-password")->name("user.forgotpassword"));
Router::get((new Route([AuthentificationController::class, "displayResetPasswordForm"]))->name('user.resetpassword')->path("/user/reset-password"));
Router::get((new Route([AuthentificationController::class, "logout"]))->name("user.logout")->path("/user/logout"));
Router::post((new Route([AuthentificationController::class, "login"]))->name("user.login")->path("/user/login"));
Router::post((new Route([AuthentificationController::class, "register"]))->name("user.register")->path("/user/register"));
Router::post((new Route([AuthentificationController::class, "sendLinkToResetPassword"]))->name("user.forgotpassword")->path("/user/forgot-password"));
Router::post((new Route([AuthentificationController::class, "resetPassword"]))->name("user.resetpassword")->path("/user/reset-password"));
Router::post((new Route([AuthentificationController::class, "handleResetPassword"]))->name("user.handleresetpassword")->path("/user/handle-reset-password"));



