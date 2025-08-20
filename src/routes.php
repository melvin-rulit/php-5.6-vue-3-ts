<?php
global $router;

use App\Http\Controllers\BotController;
use App\Http\Controllers\FormController;

//use App\Controllers\FormController;


// Telegram webhook
$router->post('/bot/webhook', [BotController::class, 'handleWebhook']);

// Форма А
$router->post('/form/a', [FormController::class, 'handleFormA']);

// Форма Б
$router->post('/form/b', [FormController::class, 'handleFormB']);