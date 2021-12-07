<?php

namespace App\Controllers\Home;

use App\Exceptions\ModelColumnNotfound;
use App\Models\User;
use Core\Request;

class HomeController
{

    /**
     * @throws ModelColumnNotfound
     */
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