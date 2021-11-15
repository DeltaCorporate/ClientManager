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

       self::$renderer->render("test1",[
           "app"=>$_SERVER['APP_NAME']
       ]);
   }

   public function index2(){
       echo "test fait 2";
   }
}