<?php

namespace App\Views;

use App\Models\User;
use Core\Csrf;
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
            echo url($name, $reqMethode, $datas);

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
        self::$renderer->addFunction(new TwigFunction("csrf", function ($time = 3600) {
            $csrf= new Csrf($time);
            $token = $csrf->getToken();
            echo "<input type='hidden' name='csrf' value='$token'>";
        }));
        self::$renderer->addFunction(new TwigFunction("csrf_token", function ($time = 3600) {
            $csrf= new Csrf($time);
            echo $csrf->getToken();
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
        self::$renderer->addFunction(new TwigFunction("avatar", function (string $name) {
            echo $_SERVER["APP_URL"] . "/src/users/avatars/" . $name;
        }));
        self::$renderer->addFunction(new TwigFunction("product", function (string $name) {
            echo $_SERVER["APP_URL"] . "/src/products/images/" . $name;
        }));
        self::$renderer->addFunction(new TwigFunction("cart", function () {
            return sizeof(Session::session("cart"));
        }));
        self::$renderer->addFunction(new TwigFunction("file_get_contents", function ($file) {
            echo file_get_contents($file);
        }));
        self::$renderer->addFunction(new TwigFunction("hasRole", function ($role) {
            return Session::getUser() && User::hasRole(Session::getUser(), $role);
        }));
    }

    public function getRenderer(): Environment
    {
        return self::$renderer;
    }


}