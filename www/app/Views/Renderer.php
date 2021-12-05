<?php

namespace App\Views;

use App\Exceptions\UndefinedOptionsException;
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
            echo url($name,$reqMethode,$datas);

        }));
        self::$renderer->addFunction(new TwigFunction("asset",function ($path){
            echo "/assets/".$path;
        }));
        self::$renderer->addFunction(new TwigFunction("image",function ($path){
            echo "/src/images/".$path;
        }));
        self::$renderer->addFunction(new TwigFunction("arrow",function (){
            echo "&#10132;";
        }));
    }

    public function getRenderer(): Environment
    {
        return self::$renderer;
    }






}