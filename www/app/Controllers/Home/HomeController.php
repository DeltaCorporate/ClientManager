<?php

namespace App\Controllers\Home;

use App\Models\User;
use Core\Request;

class HomeController
{

    public function index(): bool
    {
        return render('home.accueil');
    }
    public function test(): bool
    {

        return render('home.test',[
            "title"=>Request::get('title'),
        ]);
    }

}