<?php

namespace App\Views;

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
        self::$renderer = new Environment($this->loader, [
            'strict_variables' => false,
        ]);
        self::$renderer->addFunction(new TwigFunction("url", function ($name, $reqMethode = 'get', $datas = []) {
            echo $_SERVER["APP_URL"] . url($name, $reqMethode, $datas);

        }));
        self::$renderer->addFunction(new TwigFunction("asset", function ($path) {
            echo $_SERVER["APP_URL"] . "/assets/" . $path;
        }));
        self::$renderer->addFunction(new TwigFunction("image", function ($path) {
            echo $_SERVER["APP_URL"] . "/src/images/" . $path;
        }));
        self::$renderer->addFunction(new TwigFunction("arrow", function () {
            echo "&#10132;";
        }));
        self::$renderer->addFunction(new TwigFunction("csrf", function () {
            $token = Session::csrf();
            echo "<input type='hidden' name='csrf' value='$token'>";
        }));
        self::$renderer->addFunction(new TwigFunction("flash", function ($key) {
            return (Session::getFlash($key))['value'];
        }));
        self::$renderer->addFunction(new TwigFunction("session", function ($key) {
            $validationMessages = Session::session($key);
            $validationMessages = array_map(function ($value) {
                return $value['value'];
            }, $validationMessages);
            return ($validationMessages);
        }));
        self::$renderer->addFunction(new TwigFunction("auth", function () {
            return Session::getUser();
        }));
        self::$renderer->addFunction(new TwigFunction("public_path", function ($path) {
            echo $_SERVER['APP_URL'] . "/" . $path;
        }));
    }

    public function getRenderer(): Environment
    {
        return self::$renderer;
    }


}