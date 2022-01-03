<?php

namespace App\Controllers\Home;


use Core\Session;

class HomeController
{

    /**
     */
    public function index()
    {
        return render('home.accueil');
    }


    public function test()
    {
        return render('emails.reset-password');

    }

}