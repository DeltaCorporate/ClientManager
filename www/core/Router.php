<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 05/12/2021 at 14:00
*@Class: Router
*@NameSpace: Config
*/

namespace Core;

use App\Exceptions\MiddlewareException;
use App\Middlewares\TestMiddleware;

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


    public static function get(Route $route)
    {
        self::$routes['GET'][$route->getPath()] = $route->get();
    }

    public static function post(Route $route)
    {
        $route->middleware("csrf");
        self::$routes['POST'][$route->getPath()] = $route->get();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getSession(): Session
    {
        return $this->session;
    }


    public static function getRoutes($method): array
    {
        return self::$routes[strtoupper($method)] ?? [];
    }


    /**
     * @throws MiddlewareException
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callable = self::$routes[$method][$path]['callable'] ?? false;
        if ($callable === false or !is_callable($callable)) {
            render('errors.404');
        }
        $middlewares = self::$routes[$method][$path]['middlewares'] ?? [];
        if (!empty($middlewares)) {
            Middleware::checkMiddlewares($middlewares);
            Middleware::resolve($middlewares, $this->request, $this->session, $callable);
        } else {
            call_user_func_array($callable, [$this->request, $this->session]);
        }

    }


}