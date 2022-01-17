<?php

namespace App\Controllers\Home;


use App\Models\Review;
use App\Models\User;
use App\Models\User_data;
use Core\Session;

class HomeController
{

    /**
     */
    public function index()
    {
        dd(Review::findByandBy(array("user_id"=>1,"testimonial_id"=>1)));
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