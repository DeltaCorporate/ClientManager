<?php

namespace App\Views;

use Config\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

require "../vendor/autoload.php";

class Renderer
{
    private FilesystemLoader $loader;
    public static Environment $renderer;

    public function __construct()
    {


        $this->loader = new FilesystemLoader(ROOT.'/ressources/views/');
        self::$renderer = new Environment($this->loader);
        self::$renderer->addFunction(new TwigFunction("url",function ($name,$reqMethode,$datas=[]){
            echo Router::generateURL($name,$reqMethode,$datas);
        }));
        self::$renderer->addFunction(new TwigFunction("asset",function ($path){
            echo "/assets/".$path;
        }));
    }

    public function getRenderer(): Environment
    {
        return self::$renderer;
    }






}