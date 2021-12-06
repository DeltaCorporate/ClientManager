<?php

namespace App\Controllers\Home;

use Core\Request;

class HomeController
{

    public function index(): bool
    {
        return render('home.accueil');
    }
    public function test(): bool
    {
        session("error",'Vous êtes connecté');
        redirect('home');
        return render('home.test',[
            "title"=>Request::get('title'),
        ]);
    }

}