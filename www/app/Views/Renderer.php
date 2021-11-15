<?php

namespace App\Views;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require "../vendor/autoload.php";

class Renderer
{
    private $loader;
    public static $renderer;

    public function __construct()
    {

        $this->loader = new \Twig\Loader\FilesystemLoader('../Templates');
        self::$renderer = new \Twig\Environment($this->loader);
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