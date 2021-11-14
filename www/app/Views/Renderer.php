<?php

namespace App\Views;

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


    public static function render($path, $datas = null)
    {

        $path = str_replace(".", "/", $path);
        $path .= ".html.twig";

        if (!empty($datas)) {
            echo self::$renderer->render($path, $datas);
        } else {
            echo self::$renderer->render($path);
        }

    }


}