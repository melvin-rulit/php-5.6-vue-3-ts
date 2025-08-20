<?php

namespace App\Services;

class OpenMeteoService
{
    /**
     * Получить текущую погоду по координатам
     *
     * @param mixed $latitude
     * @param mixed $longitude
     * @return array|null
     */
    public function getCurrentWeather($latitude, $longitude)
    {
        $url = "https://api.open-meteo.com/v1/forecast?"
            . "latitude={$latitude}&longitude={$longitude}"
            . "&current_weather=true&hourly=temperature_2m,relativehumidity_2m,pressure_msl,precipitation";

        $response = @file_get_contents($url);
        if (!$response) {
            return null;
        }

        $data = json_decode($response, true);
        if (!isset($data['current_weather'])) {
            return null;
        }

        $weather = $data['current_weather'];

        // Получаем дополнительные параметры из hourly
        $hourIndex = array_search($weather['time'], $data['hourly']['time']);
        $humidity = isset($data['hourly']['relativehumidity_2m'][$hourIndex]) ? $data['hourly']['relativehumidity_2m'][$hourIndex] : null;
        $pressure = isset($data['hourly']['pressure_msl'][$hourIndex]) ? $data['hourly']['pressure_msl'][$hourIndex] : null;
        $precipitation = isset($data['hourly']['precipitation'][$hourIndex]) ? $data['hourly']['precipitation'][$hourIndex] : null;

        return array(
            'temperature' => $weather['temperature'],
            'windspeed' => $weather['windspeed'],
            'winddirection' => $weather['winddirection'],
            'humidity' => $humidity,
            'pressure' => $pressure,
            'precipitation' => $precipitation,
            'time' => $weather['time']
        );
    }
}