<?php

namespace App\Controllers\Home;


use App\Models\User;
use App\Models\User_data;
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
        return render('emails.register',[
            "token"=>token(),
            "user"=>User::find(1),
        ]);

    }

}