<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Webhook URL который вы укажете в BotFather
Route::post('/api/telegram/webhook', [TelegramController::class, 'handle']);