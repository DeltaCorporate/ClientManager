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
    private Session $session;

    public function __construct(Request $request, Session $session)
    {
        require_once('../routes/web.php');
        $this->request = $request;
        $this->session = $session;
    }

    public static function get2($path, $callable, $name)
    {
        self::$routes['GET'][$path] = [
            'path' => $path,
            'callable' => $callable,
            'name' => $name
        ];
    }
    public static function get(Route $route)
    {
        self::$routes['GET'][$route->getPath()] = $route->get();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public static function post2($path, $callable, $name)
    {
        self::$routes['POST'][$path] = [
            'path' => $path,
            'callable' => $callable,
            'name' => $name
        ];
    }
    public static function post(Route $route)
    {
        self::$routes['POST'][$route->getPath()] = $route->get();
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

        if (is_callable($callable)) {
            return call_user_func_array($callable, [$this->request, $this->session]);
        } else {
            return render('errors.404');
        }

    }


}