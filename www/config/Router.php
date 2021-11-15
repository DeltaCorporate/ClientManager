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
            $route['function'] = $toDo[1];
            array_push(self::$routes,$route);
        }

    }

    static function checkRoute(){
        $request = $_SERVER;
        $requestUri = $request['REQUEST_URI'];
        $requestUri = explode("?",$requestUri);
        $requestUri = $requestUri[0];
        $requestUri = str_split($requestUri);
        if($requestUri[sizeof($requestUri)-1] == "/"){
            array_splice($requestUri,sizeof($requestUri)-1);
        }
        $requestUri = implode($requestUri);
        $routes = self::$routes;


        $foundRoute = false;
        foreach ($routes as $route){
            if($route['link'] == $requestUri){
                $foundRoute = true;
                $controller = $route["controller"];
                $function = $route["function"];
                (new $controller())->$function();
            }
        }

        if(!$foundRoute){
            return self::$renderer->render("errors.404");
        }

    }


}