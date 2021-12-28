<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 25/12/2021 at 18:37
*@Class: AuthMiddleware
*@NameSpace: App\Middlewares
*/

namespace App\Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Session;

class AuthMiddleware extends Middleware
{

    public function run(Request $request,Session $session):bool
    {
        return true;
    }

    public function error()
    {
        render("errors.404");
    }
}