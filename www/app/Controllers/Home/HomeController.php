<?php

namespace App\Controllers\Home;

class HomeController
{
    public function index(): bool
    {
        dd([date('YmdHis')]);
        return render('home.accueil', [
            'title' => "Page d'accueil",
        ]);
    }

}