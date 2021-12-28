<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 25/12/2021 at 14:02
*@Class: Middleware
*@NameSpace: Core
*/

namespace Core;


use App\Exceptions\MiddlewareException;
use App\Middlewares\ConnectedMiddleware;

abstract class Middleware
{
    public static function all(): array
    {
        return [
            "auth" => ConnectedMiddleware::class
        ];
    }

    /**
     * @param array $middlewares
     * @throws MiddlewareException
     */
    public static function checkMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            if (!array_key_exists($middleware, self::all())) {
                throw new MiddlewareException("Middleware $middleware not found");
            }
        }
    }


    public static function resolve(array $middlewares, Request $request, Session $session, callable $callable)
    {
        $next = true;
        $middlewareError = "";
        $allMiddlewares = self::all();
        foreach ($middlewares as $middleware) {
            $next = call_user_func_array([$allMiddlewares[$middleware], 'run'], [$request, $session]);
            if (!$next) {
                $middlewareError = $middleware;
                break;
            }
        }
        if (!$next) {
            call_user_func([$allMiddlewares[$middlewareError], 'error']);
        } else {
            call_user_func_array($callable, [$request, $session]);
        }
    }

    public abstract function run(Request $request, Session $session): bool;

    public abstract function error();

}