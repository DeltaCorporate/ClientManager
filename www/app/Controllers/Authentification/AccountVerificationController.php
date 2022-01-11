<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: AccountVerificationController
*@NameSpace: App\Controllers
*/

namespace App\Controllers\Authentification;

use App\Models\AccountVerifToken;
use App\Models\User;
use Core\Request;
use Core\Session;

class AccountVerificationController
{
    /**
     * @throws \App\Exceptions\ModelColumnNotfound
     */
    public function verify(Request $request, Session $session)
    {
        $values = $request->getBody();
        $rules = [
          "id"=>["required","int"],
          "token"=>["required","string"]
        ];
        $values = User::associateRulesAndDatas($values,$rules,["id","token"]);
        Request::validateRules($values);
        $user = User::find($values["id"]["value"]);

        $verifToken = $user->verifyToken;

        if($verifToken->token == $values["token"]["value"]){
            User::update($user->id,["verified"=>1]);
            AccountVerifToken::delete($verifToken->id);
            flash("success","Your account has been verified successfully");
        }else{
            flash("error","Your account has not been verified! Please retry!");
        }
        redirect("user.login");

    }
}
