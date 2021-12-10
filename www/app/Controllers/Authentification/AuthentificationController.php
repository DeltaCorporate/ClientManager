<?php

namespace App\Controllers\Authentification;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;
use Core\Request;
use Core\Session;

class AuthentificationController
{
    /**
     * Methods to display forms
     */
    public function displayLoginForm(): bool
    {
        if(Session::getUser()){
            flash("info", "You are already logged in!");
            redirect("home");
        }
        return render('authentification/login');
    }

    public function displayRegisterForm(): bool
    {
        return render('authentification/register');
    }

    public function displayForgotPasswordForm(): bool
    {
        return render('authentification/forgot-password');
    }

    public function displayResetPasswordForm(): bool
    {
        return render('authentification/reset-password');
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function register()
    {

        $values = Request::postBody();
        $rules = [
            'email' => ["required", "email"],
            "username" => ["string","length:6:20"],
            "password" => ["string","length:8:20"],
            "password_confirm" => ["string","length:8:20"],
        ];
        User::checkPasswordConfirm($values['password'], $values['password_confirm']);
        $values = User::matchPostValuesToValidationData($values, $rules);
        Request::validateRules($values);

        User::checkIfUniqueRespected($values['email']['value']);


        $user = new User();
        $user->setUsername($values['username']['value']);
        $user->setEmail($values['email']['value']);
        $password = password_hash($values['password']['value'], PASSWORD_ARGON2I);
        $user->setPassword($password);
        User::save($user->getUser());
        flash("success", "You have been registered! An email was sent to verify your account!");
        redirect("user.login");

    }

    public function login(){
        if(Session::getUser()){
            flash("info", "You are already logged in!");
            redirect("user.home");
        }
        $values = Request::postBody();
        $rules = [
            "email" => ["required"],
            "password" => ["required"],
        ];
        $values = User::matchPostValuesToValidationData($values, $rules,User::getColumnsToLogin(),false);

        Request::validateRules($values);

        $user = User::findBy('email', $values['email']['value']);
        if(!$user){
            flash("error", "Email or password is incorrect!");
            redirect("user.login");
        }
        if(!password_verify($values['password']['value'], $user->password)){
            flash("error", "Email or password is incorrect!");
            redirect("user.login");
        }
//        if(!$user->getIsVerified()){
//            flash("error", "Your account is not verified!");
//            redirect("user.login");
//        }
        Session::setUser($user->email);
        flash("success", "You have been logged in!");
        redirect("home");
    }

    public function logout(){
        Session::removeUser();
        flash("success","You have been logged out!");
        redirect("home");
    }

}