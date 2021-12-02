<?php

namespace App\Controllers\Home;

use App\Views\Renderer;

class HomeController
{
    public function index()
    {
        return Renderer::render("home.accueil");

    }

}