<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Class: AuthMiddleware
*@NameSpace: App\Middlewares
*/

namespace App\Middlewares;

use App\Models\User;
use Core\Middleware;
use Core\Request;
use Core\Session;

class AdminMiddleware extends Middleware
{

    public function run(Request $request, Session $session): bool
    {
        $user = Session::getUser();
        return User::hasRole($user, 'administrator');
    }

    public function error()
    {
        flash("error","You don't have permission to access this page");
        redirect("user.profile");
    }
}
