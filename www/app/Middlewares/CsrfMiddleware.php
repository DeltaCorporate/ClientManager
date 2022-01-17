<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: AuthMiddleware
*@NameSpace: App\Middlewares
*/

namespace App\Middlewares;

use Core\Csrf;
use Core\Middleware;
use Core\Request;
use Core\Session;

class CsrfMiddleware extends Middleware
{

    public function run(Request $request, Session $session): bool
    {
        $token = Request::postBody()["csrf"];
        $tokens = Session::session("csrf");
        foreach ($tokens as $key => $csrf) {
            if ($csrf->getToken() == $token and time() < $csrf->getExpireAt()) {
                unset($tokens[$key]);
                Session::setSession("csrf", $tokens);
                return true;
            }
        }
        return false;
    }


    public function error()
    {
        return render("errors.419");
    }
}
