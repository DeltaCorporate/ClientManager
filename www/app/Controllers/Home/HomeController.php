<?php

namespace App\Controllers\Home;

use Core\Request;
use Core\Session;

class HomeController
{

    public function index(): bool
    {
        return render('home.accueil');
    }
    public function test(): bool
    {

        redirect('home');
        return render('home.test',[
            "title"=>Request::get('title'),
        ]);
    }

}