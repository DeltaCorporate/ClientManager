<?php

namespace App\Controllers\Authentification;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;
use Core\Request;

class AuthentificationController
{
    /**
     * Methods to display forms
     */
    public function displayLoginForm(): bool
    {
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

}