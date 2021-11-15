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


    public static function get($link,$toDo,$name =null): ?bool
    {
        $route = [];
        if(empty($link) || empty($toDo)){
            return self::$renderer->render("errors.404");
        } else{
            $route['link'] = $link;
            $route['method'] = "GET";
            $route['controller'] = $toDo[0];
            $route['function'] = $toDo[1];
            array_push(self::$routes,$route);
        }

        return null;
    }
    public static function post($link,$toDo,$name =null): ?bool
    {

        $route = [];
        if(empty($link) || empty($toDo)){
            return self::$renderer->render("errors.404");
        } else{
            $route['link'] = $link;
            $route['method'] = "POST";
            $route['controller'] = $toDo[0];
            $route['function'] = $toDo[1];
            array_push(self::$routes,$route);
        }
        return null;
    }

    static function checkRoute(){
        $request = $_SERVER;
        $requestUri = $request['REQUEST_URI'];
        $requestUri = explode("?",$requestUri);
        $requestUri = $requestUri[0];
        if($requestUri != "/"){
            $requestUri = str_split($requestUri);
            if($requestUri[sizeof($requestUri)-1] == "/"){
                array_splice($requestUri,sizeof($requestUri)-1);
            }
            $requestUri = implode($requestUri);
        }

        $routes = self::$routes;


        $foundRoute = false;
        foreach ($routes as $route){
            if($route['link'] == $requestUri and $request['REQUEST_METHOD'] == $route['method']){

                $controller = $route["controller"];
                $function = $route["function"];
                $foundRoute = true;
                (new $controller())->$function();
                return true;


            }
        }


        if(!$foundRoute){
            self::$renderer->render("errors.404");
        }
        return false;

    }


}