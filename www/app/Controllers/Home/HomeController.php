<?php

namespace App\Controllers\Home;

class HomeController
{
    public function index(): bool
    {
        return render('home.accueil');
    }

}