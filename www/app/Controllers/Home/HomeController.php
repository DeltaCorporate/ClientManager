<?php

namespace App\Controllers\Home;


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
        sendMail(
            ["email" => "sender@gmail.com", "name" => "ClientManager"],
            ["email" => "recepteur@gmail.com", "name" => "Raphael"],
            "Test",
            "emails.register",
            [["email" => "houmame@gmail.com"], ["email" => "raphael@gmail.com"]]
        );

    }

}