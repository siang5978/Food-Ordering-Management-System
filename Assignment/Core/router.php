<?php
namespace Core;

class router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, callable|array $callback): void
    {
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = "#^$pattern$#";
        $this->routes[$method][$pattern] = $callback;
    }

    public function resolve(): mixed
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = explode('?', $path)[0];

        foreach ($this->routes[$method] ?? [] as $pattern => $callback) {
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, fn($k) => !is_numeric($k), ARRAY_FILTER_USE_KEY);

                if (is_array($callback)) {
                    [$class, $action] = $callback;
                    $controller = new $class();
                    return $controller->$action($params);
                }
                return $callback($params);
            }
        }

        http_response_code(404);
        return "This is a error Page";
    }
}