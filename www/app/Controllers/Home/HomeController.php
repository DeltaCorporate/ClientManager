<?php

namespace App\Controllers\Home;

class HomeController
{

    public function index(): bool
    {
        dd(url("home",'get'));
        return render('home.accueil');
    }

}