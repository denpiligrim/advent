<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdventBotService;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2026-01-01 10:00:00'));
        // Инициализация сервиса
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $botService = new AdventBotService($telegram);
        
        // Передача обновления в сервис
        $botService->handleUpdate($telegram->getWebhookUpdate());

        return response('OK', 200);
    }
}