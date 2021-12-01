<?php

namespace App\Views;

use Config\Router;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
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



    public static function render($path, $datas = []): bool
    {

        $path = str_replace(".", "/", $path);
        $path .= ".html.twig";
        try {
            echo self::$renderer->render($path, $datas);
            return true;
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo $e->getMessage();

            return false;
        }

    }


}