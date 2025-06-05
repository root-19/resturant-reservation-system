<?php
namespace root_dev;

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Check if route exists
        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            
            // Extract parameters from URL if they exist
            $params = [];
            if (preg_match_all('/\{([^}]+)\}/', $path, $matches)) {
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $path);
                $pattern = str_replace('/', '\/', $pattern);
                if (preg_match('/^' . $pattern . '$/', $path, $values)) {
                    array_shift($values);
                    $params = array_combine($matches[1], $values);
                }
            }
            
            return call_user_func($callback, $params);
        }
        
        return false;
    }
}
