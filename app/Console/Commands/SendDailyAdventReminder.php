<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramUser;
use App\Models\Task;
use Telegram\Bot\Api;
use Carbon\Carbon;

class SendDailyAdventReminder extends Command
{
    // Название команды для запуска вручную: php artisan advent:send-reminders
    protected $signature = 'advent:send-reminders';
    protected $description = 'Рассылка ежедневных напоминаний пользователям';

    public function handle()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        // $today = Carbon::today();
        $today = Carbon::parse('2026-01-01'); // Для теста можно подставить Carbon::parse('2026-01-01')

        // Проверяем, есть ли вообще задания на сегодня
        $hasTasks = Task::whereDate('active_date', $today)->exists();

        if (!$hasTasks) {
            $this->info("На сегодня ($today) заданий нет. Рассылка отменена.");
            return;
        }

        // Получаем всех пользователей бота
        $users = TelegramUser::all();

        $keyboard = json_encode([
            'inline_keyboard' => [[
                ['text' => "🚀 Поехали!", 'callback_data' => 'start_daily_tasks']
            ]]
        ]);

        foreach ($users as $user) {
            try {
                $telegram->sendMessage([
                    'chat_id' => $user->chat_id,
                    'text' => "Доброе утро, <b>{$user->first_name}</b>! ❄️\nНовые задания уже ждут тебя в календаре. Готов продолжить?",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $keyboard
                ]);
            } catch (\Exception $e) {
                // Если пользователь заблокировал бота, логируем это
                \Log::error("Не удалось отправить напоминание пользователю {$user->chat_id}: " . $e->getMessage());
            }
        }

        $this->info("Рассылка завершена для " . $users->count() . " пользователей.");
    }
}