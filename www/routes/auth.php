<?php


use App\Controllers\Authentification\LoginController;
use App\Controllers\Authentification\PasswordResetController;
use App\Controllers\Authentification\RegisterController;
use Core\Route;
use Core\Router;

Router::get((new Route([LoginController::class, "displayLoginForm"]))->name("user.login")->path("/user/login")->middleware("guest"));
Router::get((new Route([RegisterController::class, 'displayRegisterForm']))->name("user.register")->path("/user/register")->middleware("guest"));
Router::get((new Route([PasswordResetController::class, "displayForgotPasswordForm"]))->path("/user/forgot-password")->name("user.forgotpassword")->middleware("guest"));
Router::get((new Route([PasswordResetController::class, "displayResetPasswordForm"]))->name('user.displayresetpassword')->path("/user/display-reset-password")->middleware("guest"));
Router::get((new Route([LoginController::class, "logout"]))->name("user.logout")->path("/user/logout"));


Router::post((new Route([LoginController::class, "login"]))->name("user.login")->path("/user/login")->middleware("guest"));
Router::post((new Route([RegisterController::class, "register"]))->name("user.register")->path("/user/register")->middleware("guest"));
Router::post((new Route([PasswordResetController::class, "sendLinkToResetPassword"]))->name("user.forgotpassword")->path("/user/forgot-password")->middleware("guest"));
Router::get((new Route([PasswordResetController::class, "resetPassword"]))->name("user.resetpassword")->path("/user/reset-password")->middleware("guest"));
Router::post((new Route([PasswordResetController::class, "handleResetPassword"]))->name("user.handleresetpassword")->path("/user/handle-reset-password")->middleware("guest"));



