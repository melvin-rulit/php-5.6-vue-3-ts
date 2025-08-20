<?php

namespace App\Models;

use PDO;
use Core\DBConnect;

class CityModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DBConnect::getInstance();
    }

    public function getAllCities()
    {
        $stmt = $this->pdo->query("SELECT * FROM cities ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCityById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cities WHERE id = ?");
        $stmt->execute([$id]);
        $city = $stmt->fetch(PDO::FETCH_ASSOC);
        return $city ?: null;
    }
}