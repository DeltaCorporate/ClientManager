<?php

namespace Config;

use App\Views\Renderer;
use App\Views\Views;

require "../vendor/autoload.php";

/**
 * @property array $routes
 */
class Router
{
    private static array $routes;
    private static Renderer $renderer;
    public function __construct()
    {
        self::$routes = [];
        self::$renderer = new Renderer();
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * @return Renderer
     */
    public function getRenderer(): Renderer
    {
        return self::$renderer;
    }

    public function addRoutes(){
        include_once "../routes/web.php";
    }


    public static function get($link,$toDo,$name =null){
        if($_SERVER['REQUEST_METHOD'] != "GET"){
            self::$renderer->render("errors.404");
        }
        $route = [];
        if(empty($link) || empty($toDo)){
            self::$renderer->render("errors.404");
        } else{
            $route['link'] = $link;
            $route['controller'] = $toDo[0];
            $route['method'] = $toDo[1];
            array_push(self::$routes,$route);
        }

    }

    static function checkRoute(){
        $request = $_SERVER;
        $requestUri = $request['REQUEST_URI'];
        $requestUri= explode("/",$requestUri);
        if($requestUri[0] == "" && $requestUri[1]==""){
            $requestUri = array_splice($requestUri,1);
            $requestUri[0] = "/";
        }
        Views::dd($requestUri);

        $routes = self::$routes;

        foreach ($routes as $route){

        }

    }


}