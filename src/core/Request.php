<?php
namespace Core;

class Request
{
    /**
     * Получить HTTP метод
     */
    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Получить путь запроса
     */
    public function path() {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        return rtrim($uri, '/');
    }

    /**
     * Получить тело запроса
     */
    public function input() {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        return $data ?: $_POST;
    }
}
