<?php

namespace App\Views;

use Core\Request;
use Core\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;


class Renderer
{
    private FilesystemLoader $loader;
    public static Environment $renderer;

    public function __construct()
    {


        $this->loader = new FilesystemLoader(ROOT . '/ressources/views/');
        self::$renderer = new Environment($this->loader,[
            'strict_variables' => false,
        ]);
        self::$renderer->addFunction(new TwigFunction("url", function ($name, $reqMethode, $datas = []) {
            echo url($name, $reqMethode, $datas);

        }));
        self::$renderer->addFunction(new TwigFunction("asset", function ($path) {
            echo "/assets/" . $path;
        }));
        self::$renderer->addFunction(new TwigFunction("image", function ($path) {
            echo "/src/images/" . $path;
        }));
        self::$renderer->addFunction(new TwigFunction("arrow", function () {
            echo "&#10132;";
        }));
        self::$renderer->addFunction(new TwigFunction("csrf", function () {
            $token = Request::csrf();
            echo "<input type='hidden' name='csrf' value='$token'>";
        }));
        self::$renderer->addFunction(new TwigFunction("session", function ($key) {
            return (Session::getFlash($key))['value'];
        }));
    }

    public function getRenderer(): Environment
    {
        return self::$renderer;
    }


}