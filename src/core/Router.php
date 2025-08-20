<?php

namespace Core;

class Router
{
    /**
     * @var array
     * Хранилище маршрутов вида:
     * ['GET' => ['/path' => callback], 'POST' => ['/path' => callback]]
     */
    private $routes = [];

    /**
     * @var Request
     * Текущий HTTP-запрос
     */
    private $request;

    /**
     * Конструктор роутера
     *
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function get($path, $callback) {
        $this->routes['GET'][rtrim($path, '/')] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][rtrim($path, '/')] = $callback;
    }

    public function resolve() {
        $method = $this->request->method();
        $path = rtrim($this->request->path(), '/');

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];

            if (is_array($callback)) {
                $controller = new $callback[0]();
                return call_user_func([$controller, $callback[1]], $this->request);
            }

            return call_user_func($callback, $this->request);
        }

        http_response_code(404);
        echo "404 Not Found";
        return null;
    }
}