<?php

namespace App\Controllers;

use App\Views\Renderer;

class Test
{
    private static Renderer $renderer;
    public function __construct()
    {
        self::$renderer = new Renderer();
    }

    public function index(){
       self::$renderer->render("test1");
   }

   public function index2(){
       echo "test fait 2";
   }
}