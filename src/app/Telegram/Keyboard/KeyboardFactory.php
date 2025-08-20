<?php

namespace App\Telegram\Keyboard;

class KeyboardFactory
{
    public static function cityKeyboard(array $cities)
    {
        $keyboard = ['inline_keyboard' => []];
        $row = [];

        foreach ($cities as $city) {
            $row[] = [
                'text' => $city['name'],
                'callback_data' => 'city_' . $city['id']
            ];

            if (count($row) === 2) {
                $keyboard['inline_keyboard'][] = $row;
                $row = [];
            }
        }

        if (!empty($row)) {
            $keyboard['inline_keyboard'][] = $row;
        }

        return $keyboard;
    }

    /**
     * Создаёт одну кнопку с кастомным текстом и callback_data
     *
     * @param string $text
     * @param string $callbackData
     * @return array
     */
    public static function singleButtonKeyboard($text, $callbackData)
    {
        return [
            'inline_keyboard' => [
                [
                    ['text' => $text, 'callback_data' => $callbackData]
                ]
            ]
        ];
    }
}