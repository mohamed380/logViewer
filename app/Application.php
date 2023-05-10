<?php 

namespace App;

final class Application {

    private $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->registerRoutes();
    }

    private function registerRoutes(): self
    {
        $web = require __DIR__ . '/routes/web.php';
        $api = require __DIR__ . '/routes/api.php';
        foreach (array_merge($web, $api) as $routeData) {

            $this->router->register($routeData[0], $routeData[1], $routeData[2] ?? 'GET');

        }
        
        return $this;
    }

    public function resolve(string $route, string $method = 'GET'): mixed 
    {

       return $this->router->resolve($route, $method);

    }
}