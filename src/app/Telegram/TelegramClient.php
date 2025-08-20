<?php

namespace App\Telegram;

class TelegramClient
{
    private $botToken;

    public function __construct($botToken)
    {
        $this->botToken = $botToken;
    }

    /**
     * Отправка сообщения с текстом и клавиатурой
     *
     * @param int|string $chatId
     * @param string $text
     * @param array|null $keyboard
     * @return string|false JSON-ответ Telegram API или false в случае ошибки
     */
    public function sendMessage($chatId, $text, $keyboard = null)
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $postData = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];

        if ($keyboard) {
            $postData['reply_markup'] = json_encode($keyboard);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Удаление сообщения по chat_id и message_id
     *
     * @param int|string $chatId
     * @param int $messageId
     * @return string|false JSON-ответ Telegram API или false в случае ошибки
     */
    public function deleteMessage($chatId, $messageId)
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/deleteMessage";

        $postData = [
            'chat_id' => $chatId,
            'message_id' => $messageId
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}