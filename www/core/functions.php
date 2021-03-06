<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 10:07
*/

use App\Views\Renderer;
use Core\Mailer;
use Core\Router;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


function render($path, $datas = [], $renderContent = false)
{
    $renderer = (new Renderer())->getRenderer();
    $path = str_replace(".", "/", $path);
    $path .= ".html.twig";
    try {
        if ($renderContent) {
            return $renderer->render($path, $datas);
        }
        echo $renderer->render($path, $datas);

    } catch (LoaderError|RuntimeError|SyntaxError $e) {
        echo $e->getMessage();
    }

    exit();

}


function sendMail(array $from, array $to, string $subject, string $body, array $data = [], array $moreAddress = [])
{
    $mailer = new Mailer();
    $mailer->send($from, $to, $subject, $body, $data, $moreAddress);
}

function url($name, $reqMethode = "get", $datas = null): string
{

    $routes = Router::getRoutes($reqMethode);
    foreach ($routes as $route) {
        if ($route['name'] === $name) {
            return $_SERVER["APP_URL"] . $route['path'];
        }
    }
    return "";
}

function token(): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 50; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

function redirect($link = "/")
{
    $routes = Router::getRoutes('GET');
    $path = "";

    if (str_starts_with($link, '/')) {
        $path = $routes[$link]['path'];
    } else if (str_contains($link, "/")) {
        $path = $link;
    } else {

        $path = url($link);
    }

    if (!headers_sent()) {
        header("Location: $path");
        exit();
    }
}

function back()
{

    if (!headers_sent() and isset($_SERVER['HTTP_REFERER']) and !empty($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        redirect('home');
    }
}

function flash($key, $value)
{
    $_SESSION['flash'][$key] = [
        "remove" => false,
        "value" => $value
    ];
}