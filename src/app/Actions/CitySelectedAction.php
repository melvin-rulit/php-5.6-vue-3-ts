<?php

namespace App\Actions;

use RedisException;
use App\Models\CityModel;
use App\Services\RedisClient;
use App\Telegram\TelegramClient;
use App\Services\OpenMeteoService;
use App\Telegram\Keyboard\KeyboardFactory;

class CitySelectedAction
{
    private $telegram;
    private $cityModel;
    private $weatherService;

    /**
     * @var RedisClient
     */
    private $redisClient;

    public function __construct()
    {
        ini_set('log_errors', 1);
        ini_set('error_log', __DIR__ . '/../../php_errors.log');

        $this->cityModel = new CityModel();
        $this->weatherService = new OpenMeteoService();

        $config = require __DIR__ . '/../../config/bot.php';
        $this->telegram = new TelegramClient($config['token']);
        $this->redisClient = new RedisClient();
    }


    public function execute($chatId, $cityId)
    {
        $cityId = str_replace('city_', '', $cityId);
        $city = $this->cityModel->getCityById($cityId);

        if (!$city) {
            $this->telegram->sendMessage($chatId, "Город не найден.");
            return;
        }

        $messageId = $this->redisClient->get("city_list_msg:{$chatId}");
        if ($messageId) {
            $this->telegram->deleteMessage($chatId, $messageId);
            $this->redisClient->del("city_list_msg:{$chatId}");
        }

        $weather = $this->weatherService->getCurrentWeather($city['latitude'], $city['longitude']);

        $message = "Текущая погода в " . $city['name'] . "\n";

        if ($weather) {
            $message .= "🌡 Температура: {$weather['temperature']}°C\n";
            $message .= "💨 Ветер: {$weather['windspeed']} м/с, направление {$weather['winddirection']}°\n";
            $message .= "💧 Влажность: {$weather['humidity']}%\n";
            $message .= "🧭 Давление: {$weather['pressure']} гПа\n";
            $message .= "☔ Осадки: {$weather['precipitation']} мм\n";
        } else {
            $message .= "Не удалось получить данные о погоде.";
        }

        $keyboard = KeyboardFactory::singleButtonKeyboard('⬅️ Вернуться к списку городов', 'back');
        $response = $this->telegram->sendMessage($chatId, $message, $keyboard);

        // Парсим message_id из ответа Telegram
        $data = json_decode($response, true);
        if (isset($data['result']['message_id'])) {
            $this->redisClient->set("city_info_msg:{$chatId}", $data['result']['message_id']);
        }
    }
}
