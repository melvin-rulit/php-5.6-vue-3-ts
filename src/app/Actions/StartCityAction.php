<?php

namespace App\Actions;

use RedisException;
use App\Models\CityModel;
use App\Services\RedisClient;
use App\Telegram\TelegramClient;
use App\Telegram\Keyboard\KeyboardFactory;

class StartCityAction
{
    private $telegram;
    private $cityModel;
    private $redisClient;
    private $logFile;

    public function __construct()
    {
//        $this->logFile = __DIR__ . '/../../php_errors.log';
//        ini_set('log_errors', 1);
//        ini_set('error_log', $this->logFile);

        $this->cityModel = new CityModel();
        $config = require __DIR__ . '/../../config/bot.php';
        $this->telegram = new TelegramClient($config['token']);
        $this->redisClient = new RedisClient();
    }


    /**
     * @throws RedisException
     */
    public function execute($chatId)
    {
        $cityInfoMsgId = $this->redisClient->get("city_info_msg:{$chatId}");
        if ($cityInfoMsgId) {
            $this->telegram->deleteMessage($chatId, $cityInfoMsgId);
            $this->redisClient->del("city_info_msg:{$chatId}");
        }

        $cities = $this->cityModel->getAllCities();
        $keyboard = KeyboardFactory::cityKeyboard($cities);
        $response = $this->telegram->sendMessage($chatId, "Выберите город для получения информации:", $keyboard);

        // Парсим message_id из ответа Telegram
        $data = json_decode($response, true);
        if (isset($data['result']['message_id'])) {
            $this->redisClient->set("city_list_msg:{$chatId}", $data['result']['message_id']);
        }
    }

//        private function log($message)
//    {
//        error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, $this->logFile);
//    }
}