<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 04/12/2021 at 10:07
*/

use App\Views\Renderer;
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
    } catch (LoaderError | RuntimeError | SyntaxError $e) {
        echo $e->getMessage();

        return false;
    }

}