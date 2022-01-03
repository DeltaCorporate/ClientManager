<?php
/*
*@Author: Houmame LAZAR <houms937@gmail.com>
*@Date: 25/12/2021 at 10:53
*@Class: Route
*@NameSpace: Core
*/

namespace Core;

class Route
{
    private string $path;
    private string $name;
    private array $callable;
    private array $params;
    private array $middleware;
    private array $middleware_params;

    public function __construct(array $callable)
    {
        $this->callable = $callable;
        $this->middleware = [];
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Route
     */
    public function path(string $path): Route
    {
        $this->path = $path;
        return $this;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Route
     */
    public function name(string $name): Route
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getCallable(): array
    {
        return $this->callable;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return Route
     */
    public function setParams(array $params): Route
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }


    /**
     * @param string $middleware
     * @return Route
     */
    public function middleware(string $middleware): Route
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    /**
     * @return array
     */
    public function getMiddlewareParams(): array
    {
        return $this->middleware_params;
    }

    /**
     * @param array $middleware_params
     * @return Route
     */
    public function setMiddlewareParams(array $middleware_params): Route
    {
        $this->middleware_params = $middleware_params;
        return $this;
    }

    public function get(): array
    {
        return [
            "path" => $this->path,
            "callable" => $this->callable,
            "name" => $this->name,
            "middlewares" => $this->middleware
        ];
    }

}