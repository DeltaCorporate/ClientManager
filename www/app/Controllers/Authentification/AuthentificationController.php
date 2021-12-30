<?php

namespace App\Controllers\Authentification;

use App\Exceptions\ModelColumnNotfound;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\User_data;
use Core\Request;
use Core\Session;

class AuthentificationController
{
    /**
     * Methods to display forms
     */

    public function displayLoginForm(): bool
    {
        if (Session::getUser()) {
            flash("info", "You are already logged in!");
            redirect("home");
        }
        return render('authentification/login');
    }

    /**
     * @return bool
     */


    public function displayRegisterForm(): bool
    {
        return render('authentification/register');
    }

    /**
     * @return bool
     */
    /*
     * Reads the data from the form and creates a new user
     * */
    public function displayForgotPasswordForm(): bool
    {
        return render('authentification/forgot-password');
    }

    /**
     * @return bool
     */
    public function displayResetPasswordForm(): bool
    {
        $toReset = Session::session("reset_data");
        if (empty($toReset)) {
            flash("error", "This token is invalid please retry.");
            redirect("user.login");
        } else {
            return render('authentification/reset-password');
        }
        return false;
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function handleResetPassword()
    {
        $toReset = Session::session("reset_data");
        if (empty($toReset)) {
            flash("error", "This token is invalid please retry.");
        } else {
            $values = Request::postBody();
            $rules = [
                "password" => ["required", "length:6:20"],
                "password_confirm" => ["required"]
            ];
            $values = User::matchPostValuesToValidationData($values, $rules,['password','password_confirm']);
            Request::validateRules($values);
            User::checkPasswordConfirm($values["password"]["value"], $values["password_confirm"]["value"]);

            User::update($toReset->user_id,["password"=>password_hash($values["password"]["value"], PASSWORD_ARGON2I)]);
            flash("success", "Your password has been reset.");
            Session::clearSession("reset_data");

        }
        redirect("user.login");

    }

    /**
     * @throws ModelColumnNotfound
     */
    public function register()
    {

        $values = Request::postBody();
        $rules = [
            'email' => ["required", "email"],
            "username" => ["string", "length:6:20"],
            "password" => ["string", "length:8:20"],
            "password_confirm" => ["string", "length:8:20"],
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
        $userID = User::findBy("email", $values['email']['value'])->id;
        $user_data = [
            "user_id" => $userID,
            "avatar"=>"defaultAvatar.svg",
        ];
        User_data::save($user_data);
        flash("success", "You have been registered! An email was sent to verify your account!");
        //TODO: send email to verify account
        redirect("user.login");

    }

    /**
     * @return void
     */
    public function login()
    {
        $values = Request::postBody();
        $rules = [
            "email" => ["required"],
            "password" => ["required"],
        ];
        $values = User::matchPostValuesToValidationData($values, $rules, User::getColumnsToLogin());

        Request::validateRules($values);

        $user = User::findBy('email', $values['email']['value']);
        if (!$user) {
            flash("error", "Email or password is incorrect!");
            redirect("user.login");
        }
        if (!password_verify($values['password']['value'], $user->password)) {
            flash("error", "Email or password is incorrect!");
            redirect("user.login");
        }

        Session::setUser($user->email);
        flash("success", "You have been logged in!");
        back();
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function sendLinkToResetPassword()
    {
        $values = Request::postBody();

        $rules = [
            "email" => ["required", "email"]
        ];
        $values = PasswordReset::matchPostValuesToValidationData($values, $rules);
        Request::validateRules($values);
        $user = User::findBy('email', $values['email']['value']);
        if (!$user) {
            Session::validation("email", 'This email doesn\'t exist!');
            back();
        } else {
            $passwordReset = PasswordReset::hasUser($user->id);
            if ($passwordReset) {
                PasswordReset::deleteBy('user_id',$user->id);
            }
            $token = token();
            while (PasswordReset::checkIfTokenExists($token)) {
                $token = token();
            }
            $passwordReset = new PasswordReset();
            $passwordReset->setUser($user->id);
            $passwordReset->setToken($token);
            PasswordReset::save($passwordReset->getPasswordReset());
            $from = ["email" => 'no-reply@client-manager.com'];
            $to = ["email" => $user->email];
            $subject = "Reset your password";
            $body = "emails.reset-password";
            $data = [
                "token" => $token,
                "user" => $user
            ];
            sendMail($from, $to, $subject, $body, $data);
            flash("success", "An email was sent to reset your password!");
            redirect("user.login");
        }

    }



    public function resetPassword()
    {
        $values = Request::postBody();
        $rules = [
            "id" => ["required", "int"],
            "token" => ["required"],
        ];
        $values = PasswordReset::matchPostValuesToValidationData($values, $rules, ['id', 'token']);
        Request::validateRules($values);
        $passwordReset = PasswordReset::checkIfTokenExists($values['token']['value']);
        if (!$passwordReset) {
            flash("error", 'This link is invalid! Please try again!');
            redirect('user.forgotpassword');
        } else {
            $union = PasswordReset::hasUser($values['id']['value']);
            if ($union) {

                PasswordReset::deleteBy("token", $union->token);
                Session::setSession("reset_data", $union);
                redirect('user.resetpassword');
            } else {
                flash("error", 'This link is invalid! Please try again!');
                redirect('user.forgotpassword');
            }
        }

    }


    /**
     * @return void
     */
    public function logout()
    {
        Session::removeUser();
        flash("success", "You have been logged out!");
        redirect("home");
    }
}