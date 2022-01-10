<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: LoginController
*@NameSpace: App\Controllers\Authentification
*/

namespace App\Controllers\Authentification;

use App\Models\User;
use App\Models\User_data;
use Core\Request;
use Core\Session;

class LoginController
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
     * @return void
     */
    public function login()
    {
        $values = Request::postBody();
        $rules = [
            "email" => ["required"],
            "password" => ["required"],
        ];
        $values = User::associateRulesAndDatas($values, $rules, User::getColumnsToLogin());

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

        unset($user->password);
        $user->data = User_data::findBy("user_id",$user->id);
        Session::setUser($user);
        flash("success", "You have been logged in!");
        back();
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
