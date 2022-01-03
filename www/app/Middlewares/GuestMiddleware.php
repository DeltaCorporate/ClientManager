<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: AuthMiddleware
*@NameSpace: App\Middlewares
*/

namespace App\Middlewares;

use Core\Middleware;
use Core\Request;
use Core\Session;

class GuestMiddleware extends Middleware
{

    public function run(Request $request, Session $session): bool
    {
        $user = $session->getUser();
        return !$user;

    }

    public function error()
    {
        redirect("user.profile");
    }
}
