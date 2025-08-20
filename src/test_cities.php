<?php
require __DIR__ . '/vendor/autoload.php';

use App\Models\CityModel;

$model = new CityModel();
$cities = $model->getAllCities();

var_dump($cities);
