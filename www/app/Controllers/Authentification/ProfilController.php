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
            "avatar"=>['required',"image"]
        ];
        $values = User::matchPostValuesToValidationData($values, $rules,["avatar"]);

        Request::validateRules($values);
        $file = $values["avatar"]['value'];
        $filename =$file['name'];
        $filename = uniqid("",true)."_".$filename;
        $destination = "src/users/avatars/".$filename;
        User_data::update($user->id,["avatar"=>$filename]);
        move_uploaded_file($file["tmp_name"],$destination);
        flash("success","Votre avatar a été mis à jour");
        back();

    }
}
