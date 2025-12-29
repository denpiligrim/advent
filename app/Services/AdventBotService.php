<?php

namespace App\Services;

use App\Models\TelegramUser;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api; // Если используете SDK

class AdventBotService
{
    protected $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handleUpdate($update)
    {
        $message = $update->getMessage();
        $callback = $update->getCallbackQuery();
        
        // Получаем Chat ID и текст
        $chatId = $message ? $message->getChat()->getId() : $callback->getMessage()->getChat()->getId();
        $text = $message ? $message->getText() : null;
        $data = $callback ? $callback->getData() : null;
        $username = $message ? $message->getFrom()->getUsername() : $callback->getFrom()->getUsername();

        // 1. Находим или создаем юзера
        $user = TelegramUser::firstOrCreate(
            ['chat_id' => $chatId],
            ['username' => $username, 'first_name' => $message ? $message->getFrom()->getFirstName() : '']
        );

        // 2. Обработка команд
        if ($text === '/start') {
            return $this->sendWelcome($user);
        }

        // 3. Обработка нажатий кнопок (для заданий без текстового ответа)
        if ($data) {
            return $this->handleCallback($user, $data);
        }

        // 4. Проверка ответа пользователя (если он сейчас выполняет задание)
        if ($user->current_task_id && $text) {
            return $this->checkAnswer($user, $text);
        }

        // 5. Дефолтный ответ на любое другое сообщение
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "🎄 Я немного занят подготовкой подарков! Если хочешь продолжить игру, нажми /start или жди следующее задание."
        ]);
    }

    protected function sendWelcome($user)
    {
        $today = Carbon::now();
        
        // Проверка: Игра идет с 1 по 11 января
        if ($today->month != 1 || $today->day > 11) {
             if ($today->month == 1 && $today->day == 12) {
                 return $this->summarizeResults($user); // Итоги 12 числа
             }
             $this->telegram->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => "Адвент-календарь еще не начался или уже закончился! 🎉"
            ]);
            return;
        }

        $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => "Привет! 🎅 Добро пожаловать в Адвент-календарь! Сегодня {$today->format('d.m')}. Давай посмотрим задания на сегодня."
        ]);

        $this->giveNextTask($user);
    }

    protected function giveNextTask(TelegramUser $user)
    {
        $today = Carbon::today();

        // Ищем задания на СЕГОДНЯ, которые юзер еще НЕ выполнил
        $doneTaskIds = $user->completedTasks()->pluck('task_id');
        
        $nextTask = Task::whereDate('active_date', $today)
            ->whereNotIn('id', $doneTaskIds)
            ->orderBy('sort_order')
            ->first();

        if (!$nextTask) {
            // Заданий на сегодня больше нет
            $user->update(['current_task_id' => null]);
            $this->telegram->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => "На сегодня заданий больше нет! Отдыхай и приходи завтра ❄️\nТвой текущий счет: {$user->total_score} баллов."
            ]);
            return;
        }

        // Назначаем текущее задание
        $user->update(['current_task_id' => $nextTask->id]);

        // Формируем клавиатуру, если задание типа 'action' (просто нажать кнопку "Готово")
        $keyboard = null;
        if ($nextTask->type === 'action') {
            $keyboard = json_encode([
                'inline_keyboard' => [[
                    ['text' => "✅ Выполнил!", 'callback_data' => 'task_done_' . $nextTask->id]
                ]]
            ]);
        }

        $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => "🎁 **Задание №{$nextTask->sort_order}**\n\n" . $nextTask->question,
            'parse_mode' => 'Markdown',
            'reply_markup' => $keyboard
        ]);
    }

    protected function checkAnswer(TelegramUser $user, $text)
    {
        $task = Task::find($user->current_task_id);
        
        // Упрощенная проверка (приводим к нижнему регистру, убираем пробелы)
        $userAnswer = trim(mb_strtolower($text));
        $correctAnswer = trim(mb_strtolower($task->correct_answer));

        if ($userAnswer == $correctAnswer) {
            $this->completeTask($user, $task);
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => "❌ Не совсем верно. Попробуй еще раз!"
            ]);
        }
    }

    protected function handleCallback($user, $data)
    {
        if (str_starts_with($data, 'task_done_')) {
            $taskId = str_replace('task_done_', '', $data);
            
            // Проверка, что юзер выполняет именно это задание
            if ($user->current_task_id != $taskId) {
                return; // Игнорируем старые кнопки
            }

            $task = Task::find($taskId);
            $this->completeTask($user, $task);
        }
    }

    protected function completeTask($user, $task)
    {
        // 1. Начисляем баллы
        $user->increment('total_score', $task->points);
        
        // 2. Записываем в историю
        $user->completedTasks()->attach($task->id);
        
        // 3. Сбрасываем текущий активный вопрос
        $user->update(['current_task_id' => null]);

        // 4. Отправляем награду
        $rewardMsg = "✅ **Верно!** Ты получил +{$task->points} баллов.";
        if ($task->reward_content) {
            $rewardMsg .= "\n\n🎁 Твой бонус:\n" . $task->reward_content;
        }

        $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => $rewardMsg,
            'parse_mode' => 'Markdown'
        ]);

        // 5. Сразу даем следующее задание (если есть)
        sleep(1); // Небольшая пауза для естественности
        $this->giveNextTask($user);
    }
    
    protected function summarizeResults($user) {
        // Логика подведения итогов
         $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => "🏁 Ивент завершен! Ты набрал {$user->total_score} баллов. Жди информацию о главном призе!"
        ]);
    }
}