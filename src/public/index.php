<?php

// подключаем Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use Core\Request;

// инициализация роутера
$router = new Router(new Request());

// подключение маршрутов
require_once __DIR__ . '/../routes.php';

// запуск
$router->resolve();