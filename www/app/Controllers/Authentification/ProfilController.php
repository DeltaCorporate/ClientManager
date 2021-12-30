<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: ProfilController
*@NameSpace: App\Controllers
*/

namespace App\Controllers\Authentification;

use App\Models\User;
use App\Models\User_data;
use Core\Request;
use Core\Session;

class ProfilController
{
    public function view()
    {
        $email = Session::getUser();
        $user = User::findBy("email", $email);
        unset($user->password);
        $user->data = User_data::findBy("user_id", $user->id);
        if (!$user->data) $user->data = new User_data();


        return render("authentification/profil", compact("user"));
    }

    public function update(Request $request)
    {
        $values = $request->postBody();
        $rules = [
            "email"=>"required|email",
            "firstname"=>"required",
            "lastname"=>"required",
            "telephone"=>"required",
            "address"=>"required",
        ];
        dd($values);

    }
}
