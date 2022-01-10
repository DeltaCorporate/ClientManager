<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: PasswordResetController
*@NameSpace: App\Controllers\Authentification
*/

namespace App\Controllers\Authentification;

use App\Exceptions\ModelColumnNotfound;
use App\Models\PasswordReset;
use App\Models\User;
use Core\Request;
use Core\Session;

class PasswordResetController
{

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
    public function sendLinkToResetPassword()
    {
        $values = Request::postBody();

        $rules = [
            "email" => ["required", "email"]
        ];
        $values = PasswordReset::associateRulesAndDatas($values, $rules);
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
            $from = ["email" => $_SERVER['FROM_EMAIL'], "name" => $_SERVER['FROM_NAME']];
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

        $values = Request::getBody();
        $rules = [
            "id" => ["required", "int"],
            "token" => ["required"],
        ];
        $values = PasswordReset::associateRulesAndDatas($values, $rules, ['id', 'token']);
        Request::validateRules($values);

        $passwordReset = PasswordReset::checkIfTokenExists($values['token']['value']);
        if (!$passwordReset) {

            flash("error", 'This link is invalid! Please try again!');
            redirect('user.forgotpassword');
        } else {
            $user = User::find($values['id']['value']);
            if ($user->resetToken) {
                $union = $user->resetToken;
                PasswordReset::deleteBy("token", $union->token);
                Session::setSession("reset_data", $union);
                redirect('user.displayresetpassword');
            } else {
                flash("error", 'This link is invalid! Please try again!');
                redirect('user.forgotpassword');
            }
        }

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
            $values = User::associateRulesAndDatas($values, $rules,['password','password_confirm']);
            Request::validateRules($values);
            User::checkPasswordConfirm($values["password"]["value"], $values["password_confirm"]["value"]);
            User::update($toReset->user_id,["password"=>password_hash($values["password"]["value"], PASSWORD_ARGON2I)]);
            $user = User::find($toReset->user_id);
            $from = ["email" => $_SERVER['FROM_EMAIL'], "name" => $_SERVER['FROM_NAME']];
            $to = ["email" => $user->email];
            $subject = "Updated password";
            $body = "emails.updated_password";
            $data = ["user" => $user];

            sendMail($from, $to, $subject, $body, $data);
            flash("success", "Your password has been reset.");
            Session::clearSession("reset_data");

        }
        redirect("user.login");

    }

}
