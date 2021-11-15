<?php

namespace App\Controllers;

use App\Views\Renderer;
use Config\Redirect;

class Test
{
    private static Renderer $renderer;
    public function __construct()
    {
        self::$renderer = new Renderer();
    }

    public function index():void{

       Redirect::redirect("test2");
   }

   public function index2(){
       self::$renderer->render("test2");
   }
}