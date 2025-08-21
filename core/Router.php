<?php

namespace app\core;

class Router
{

    protected array $routes = [];
    public function get (string $path, callable $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function resolve()
    {
        $requestedMethod = $_SERVER['REQUEST_METHOD'];
        dd($_SERVER);
    }
}