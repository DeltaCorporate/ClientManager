<?php


use App\Controllers\Authentification\AuthentificationController;
use Core\Router;

Router::get("/user/login", [AuthentificationController::class, "displayLoginForm"], "user.login");
Router::get("/user/register", [AuthentificationController::class, "displayRegisterForm"], "user.register");
Router::get("/user/forgot-password", [AuthentificationController::class, "displayForgotPasswordForm"], "user.forgotpassword");
Router::get("/user/reset-password", [AuthentificationController::class, "displayResetPasswordForm"], "user.resetpassword");

Router::post("/user/login", [AuthentificationController::class, "login"], "user.login");
Router::post("/user/register", [AuthentificationController::class, "register"], "user.register");
Router::post("/user/forgot-password", [AuthentificationController::class, "sendLinkToResetPassword"], "user.forgotpassword");
Router::post("/user/reset-password", [AuthentificationController::class, "resetPassword"], "user.resetpassword");
Router::post("/user/handle-reset-password", [AuthentificationController::class, "handleResetPassword"], "user.handleresetpassword");
Router::get("/user/logout", [AuthentificationController::class, "logout"], "user.logout");