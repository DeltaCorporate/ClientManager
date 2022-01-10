<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: RegisterController
*@NameSpace: App\Controllers\Authentification
*/

namespace App\Controllers\Authentification;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;
use App\Models\User_data;
use Core\Request;

class RegisterController
{
    public function displayRegisterForm(): bool
    {
        return render('authentification/register');
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
        $values = User::associateRulesAndDatas($values, $rules);
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
}
