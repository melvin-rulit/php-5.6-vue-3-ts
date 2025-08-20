<?php

namespace Core;

use PDO;

class DBConnect
{

    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';
        $default = $config['default'];
        $settings = $config['connections'][$default];

        if ($settings['driver'] === 'sqlite') {
            $this->pdo = new PDO("sqlite:" . $settings['database']);
        } elseif ($settings['driver'] === 'mysql') {
            $dsn = "mysql:host={$settings['host']};dbname={$settings['database']};charset={$settings['charset']}";
            $this->pdo = new PDO($dsn, $settings['username'], $settings['password']);
        }

        // Бросаем исключения при ошибках
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}