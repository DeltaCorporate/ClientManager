<?php
namespace App\Controllers\Authentification;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;
use Core\Request;

class AuthentificationController
{
    /**
     * Methods to display forms
     */
    public function displayLoginForm(): bool
    {
        return render('authentification/login');
    }
    public function displayRegisterForm(): bool
    {
        return render('authentification/register');
    }
    public function displayForgotPasswordForm(): bool
    {
        return render('authentification/forgot-password');
    }
    public function displayResetPasswordForm(): bool
    {
        return render('authentification/reset-password');
    }

    /**
     * @throws ModelColumnNotfound
     */
    public function register(){
        $values = Request::postBody();
        $user = User::findBy("email", $values['email']);
        if($user){
            session("error","This email is already used");
            redirect("user.register");
        }
        $user = new User();
        $user->setUsername($values['username']);
        $user->setEmail($values['email']);
        $password = password_hash($values['password'], PASSWORD_ARGON2I);
        $user->setPassword($password);
        User::save($user->getUser());
        session("success","You have been registered! An email was sent to verify your account!");
        redirect("user.login");

    }

}