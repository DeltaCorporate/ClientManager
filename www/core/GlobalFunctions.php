<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 10:07
*/

use App\Views\Renderer;
use Core\Router;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


function render($path, $datas = []): bool
{
    $renderer = (new Renderer())->getRenderer();
    $path = str_replace(".", "/", $path);
    $path .= ".html.twig";
    try {
        echo $renderer->render($path, $datas);
        return true;
    } catch (LoaderError|RuntimeError|SyntaxError $e) {
        echo $e->getMessage();

        return false;
    }

}

function url($name, $reqMethode, $datas = null): string
{
    $routes = Router::getRoutes($reqMethode);
    foreach ($routes as $route) {
        if($route['name']===$name){
            return $route['path'];
        }
    }
    return "";
}

function redirect($link="/")
{
    $routes = Router::getRoutes('GET');

    if (str_contains($link, '/')) {
        $link = $routes[$link]['path'];
    } else{
        $link = url($link,'get');
    }
    if(!headers_sent()){
        header("Location: $link");
        exit();
    }
}

function session($key, $value)
{
    $_SESSION['flash'][$key] = [
        "remove" => false,
        "value" => $value
    ];
}