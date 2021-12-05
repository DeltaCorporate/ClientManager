<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 14:00
*@Class: Router
*@NameSpace: Config
*/

namespace Core;

class Router
{
    protected static array $routes;
    private Request $request;

    public function __construct(Request $request)
    {
        require_once('../routes/web.php');
        $this->request = $request;
    }

    public static function get($path, $callable, $name)
    {
        self::$routes['GET'][$path] = [
            'path' => $path,
            'callable' => $callable,
            'name' => $name
        ];
    }

    public static function post($path, $callable, $name)
    {
        self::$routes['POST'][$path] = [
            'path' => $path,
            'callable' => $callable,
            'name' => $name
        ];
    }

    public static function getRoutes($method): array
    {
        return self::$routes[strtoupper($method)] ?? [];
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callable = self::$routes[$method][$path]['callable'] ?? false;
        if ($callable === false) {
            return render('errors.404');
        }
        return call_user_func($callable);

    }



}