<?php

namespace App\Http\Controllers;

use Core\Request;
use RedisException;
use App\Actions\StartCityAction;
use App\Actions\CitySelectedAction;

class BotController
{
    private $startCityAction;
    private $citySelectedAction;

    public function __construct()
    {
        $this->startCityAction = new StartCityAction();
        $this->citySelectedAction = new CitySelectedAction();
    }

    /**
     * @throws RedisException
     */
    public function handleWebhook(Request $request)
    {
        $data = $request->input();

        if (isset($data['message'])) {
            $chatId = $data['message']['chat']['id'];
            $text = isset($data['message']['text']) ? $data['message']['text'] : '';

            if ($text === '/start') {
                $this->startCityAction->execute($chatId);
            }
        }

        if (isset($data['callback_query'])) {
            $callback = $data['callback_query'];
            $chatId = $callback['message']['chat']['id'];
            $callbackData = $callback['data'];

            if (strpos($callbackData, 'city_') === 0) {
                $cityId = (int) str_replace('city_', '', $callbackData);
                $this->citySelectedAction->execute($chatId, $cityId);
            } elseif ($callbackData === 'back') {
                $this->startCityAction->execute($chatId);
            }
        }
    }
}