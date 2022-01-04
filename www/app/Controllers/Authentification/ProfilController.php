<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProfilController
*@NameSpace: App\Controllers
*/

namespace App\Controllers\Authentification;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;
use App\Models\User_data;
use Core\Request;
use Core\Session;

class ProfilController
{
    public function view()
    {

        $user = Session::getUser();
        return render("authentification/profil", compact("user"));
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function update_avatar(Request $request)
    {
        $user = Session::getUser();
        $values = $request->getFiles();
        $rules = [
            "avatar" => ['required', "image"]
        ];
        $values = User::matchPostValuesToValidationData($values, $rules, ["avatar"]);

        Request::validateRules($values);
        $file = $values["avatar"]['value'];
        $filename = $file['name'];
        $filename = uniqid($user->username . "_", true) . "_" . $filename;
        $destination = "src/users/avatars/" . $filename;
        $oldAvatar = $user->data->avatar;
        if (!empty($oldAvatar) and $oldAvatar != "defaultAvatar.svg") {
            unlink("src/users/avatars/" . $oldAvatar);
        }
        User_data::update($user->id, ["avatar" => $filename], "user_id");
        move_uploaded_file($file["tmp_name"], $destination);
        $user->data->avatar = $filename;
        Session::setUser($user);
        flash("success", "Your avatar has been updated successfully.");
        back();

    }

    /**
     * @throws ModelColumnNotfound
     */
    public function update_account_settings(Request $request)
    {
        $values = $request->postBody();
        $rules = [
            "username" => ["required", "length:3:20"],
            "email" => ["required", "email"]
        ];
        $values = User::matchPostValuesToValidationData($values, $rules, ["username", "email"]);
        Request::validateRules($values);
        $user = Session::getUser();
        $toUpdate = [
            "username" => $values["username"]["value"],
            "email" => $values["email"]["value"]
        ];
        User::update($user->id, $toUpdate);
        $user->username = $values["username"]["value"];
        $user->email = $values["email"]["value"];
        Session::setUser($user);
        flash("success", "Your account settings has been updated successfully.");
        back();
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function update_account_details(Request $request)
    {
        $values = $request->postBody();
        $rules = [
            "firstname" => ["required", "length:3:20"],
            "lastname" => ["required", "length:3:20"],
            "telephone" => ['required', 'length:10:16'],
            "address" => ["required", "length:0:255"]
        ];
        $values = User_data::matchPostValuesToValidationData($values, $rules, ["firstname", "lastname", "telephone", "address"]);
        Request::validateRules($values);
        $user = Session::getUser();
        $toUpdate = [
            "firstname" => $values["firstname"]["value"],
            "lastname" => $values["lastname"]["value"],
            "telephone" => $values["telephone"]["value"],
            "address" => $values["address"]["value"]
        ];
        User_data::update($user->id, $toUpdate, "user_id");
        $user->data->firstname = $values["firstname"]["value"];
        $user->data->lastname = $values["lastname"]["value"];
        $user->data->telephone = $values["telephone"]["value"];
        $user->data->address = $values["address"]["value"];
        Session::setUser($user);
        flash("success", "Your account details has been updated successfully.");
        back();
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function update_password(Request $request)
    {
        $values = $request->postBody();
        $rules = [
            "password" => ["required", "length:8:20"],
            "password_confirm" => ["required", "length:8:20"]
        ];
        $values = User::matchPostValuesToValidationData($values, $rules, ["password", "password_confirm"]);
        Request::validateRules($values);
        User::checkPasswordConfirm($values["password"]["value"], $values["password_confirm"]["value"]);
        $toUpdate = [
            "password" => password_hash($values["password"]["value"], PASSWORD_ARGON2I)
        ];
        $user = Session::getUser();
        User::update($user->id, $toUpdate);
        $from = ["email" => $_SERVER['FROM_EMAIL'], "name" => $_SERVER['FROM_NAME']];
        $to = ["email" => $user->email];
        $subject = "Updated password";
        $body = "emails.updated_password";
        $data = ["user" => $user];

        sendMail($from, $to, $subject, $body, $data);
        flash("success", "Your password has been updated successfully.");
        back();
    }

    public function delete_account()
    {
        $user = Session::getUser();
        User::delete($user->id);
        User_data::deleteBy("user_id", $user->id);
        $oldAvatar = $user->data->avatar;
        if (!empty($oldAvatar) and $oldAvatar != "defaultAvatar.svg") {
            unlink("src/users/avatars/" . $oldAvatar);
        }
        $from = ["email" => $_SERVER['FROM_EMAIL'], "name" => $_SERVER['FROM_NAME']];
        $to = ["email" => $user->email];
        $subject = "Deleted account";
        $body = "emails.deleted_account";
        $data = ["user" => $user];

        sendMail($from, $to, $subject, $body, $data);
        Session::removeUser();
        flash("success", "Your account has been deleted successfully.");
        redirect("user.register");
    }
}
