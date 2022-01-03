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
}
