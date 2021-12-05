<?php

namespace Config;

use App\Views\Renderer;

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
        $this->addRoutes();
        self::checkRoute();
    }

    /**
     * @return array
     */
    public static function getRoutes(): array
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

    public function addRoutes()
    {
        include_once "../routes/web.php";
    }


    public static function get($link, $toDo, $name = null): ?bool
    {
        $route = [];
        if (empty($link) || empty($toDo)) {
            return render("errors.404");
        } else {
            $route['link'] = $link;
            $route['method'] = "get";
            $route['controller'] = $toDo[0];
            $route['function'] = $toDo[1];
            if (!empty($name)) {
                $route['name'] = $name;
            }
            self::$routes[] = $route;
        }

        return null;
    }

    public static function post($link, $toDo, $name = null): ?bool
    {

        $route = [];
        if (empty($link) || empty($toDo)) {
            return render("errors.404");
        } else {
            $route['link'] = $link;
            $route['method'] = "post";
            $route['controller'] = $toDo[0];
            $route['function'] = $toDo[1];
            if (!empty($name)) {
                $route['name'] = $name;
            }
            self::$routes[] = $route;
        }
        return null;
    }

    static function checkRoute()
    {
        $request = $_SERVER;
        $requestUri = $request['REQUEST_URI'];
        $requestUri = explode("?", $requestUri);
        $requestUri = $requestUri[0];
        if ($requestUri != "/") {
            $requestUri = str_split($requestUri);
            if ($requestUri[sizeof($requestUri) - 1] == "/") {
                array_splice($requestUri, sizeof($requestUri) - 1);
            }
            $requestUri = implode($requestUri);
        }
        $routes = self::$routes;
        foreach ($routes as $route) {
            if ($route['link'] == $requestUri and strtolower($request['REQUEST_METHOD']) == $route['method']) {

                $controller = $route["controller"];
                $function = $route["function"];
                return (new $controller())->$function();
            }
        }

        return render("errors.404");

    }


}