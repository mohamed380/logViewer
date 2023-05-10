<?php 

namespace App;

final class Router{

    public const GET_METHOD = 'GET';

    private array $routes = [];
    
    public function register(string $route, array $action, string $method): self
    {
        $this->routes[$method][$route] = $action;
        return $this;
    }

    public function resolve(string $route, string $method = self::GET_METHOD): mixed
    {
        $route = parse_url($_SERVER['REQUEST_URI'])['path'];
        if(! $action = $this->routes[$method][$route] ?? null)
        throw new \Exception("Route not found", 404);
        
        [$controller, $func] = $action;

        if(!class_exists($controller))
            throw new \Exception("Class not found $controller", 500);

        return (new $controller)->$func();
    }
}