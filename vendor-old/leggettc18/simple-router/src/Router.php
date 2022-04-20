<?php

namespace leggettc18\SimpleRouter;

use Exception;

class Router {

    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Sets up routes by loading from a file containing routes.
     * 
     * Routes are declared with $route->get(uri, controller@action),
     * or $route->post(uri, controller@action).
     * 
     * @param string php file containing routes
     */
    public static function load($file) {
        $router = new static;

        require $file;

        return $router;
    }

    /**
     * Sets up a get route.
     * 
     * @param $uri
     * @param $controller 
     */
    public function get($uri, $controller) {

        $this->routes['GET'][$uri] = $controller;

    }

    /**
     * Sets up a post route.
     * 
     * @param string
     * @param string
     */
    public function post($uri, $controller) {

        $this->routes['POST'][$uri] = $controller;

    }

    /**
     * Directs requests to the appropriate controller action.
     * 
     * @param string
     * @param string
     * @return file result of call action.
     */
    public function direct($uri, $method) {

        if(array_key_exists($uri, $this->routes[$method])) {
            return $this->callAction(...explode('@', $this->routes[$method][$uri]));
        }

        throw new Exception("No route defined for $uri");

    }

    /**
     * Calls a controller action
     * 
     * @param string
     * @param string
     * @return file result of controller actionn called.
     */
    protected function callAction($controller, $action) {
        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new Exception (
                "{$controller} does not respond to the {$action} action"
            );
        }

        return $controller->$action();
    }

}

?>