<?php

namespace App\Views;

use Config\Router;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\TwigFunction;

require "../vendor/autoload.php";

class Renderer
{
    private \Twig\Loader\FilesystemLoader $loader;
    public static \Twig\Environment $renderer;

    public function __construct()
    {

        $this->loader = new \Twig\Loader\FilesystemLoader('../Templates');
        self::$renderer = new \Twig\Environment($this->loader);
        self::$renderer->addFunction(new TwigFunction("url",function ($name,$reqMethode,$datas=[]){
            echo Router::generateURL($name,$reqMethode,$datas);
        }));
    }

    public function getRenderer(): \Twig\Environment
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