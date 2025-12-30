<?php

namespace App\Services;

use App\Models\TelegramUser;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api; // Если используете SDK
use Telegram\Bot\FileUpload\InputFile;

class AdventBotService
{
    protected $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handleUpdate($update)
    {
        $message = $update->get('message');
        $callback = $update->get('callback_query');

        // Инициализируем переменные по умолчанию
        $chatId = null;
        $text = null;
        $data = null;
        $username = null;
        $firstName = 'Друг';

        // Если это обычное текстовое сообщение
        if ($message) {
            $chatId = $message->get('chat')->get('id');
            $text = $message->get('text');
            $username = $message->get('from')->get('username');
            $firstName = $message->get('from')->get('first_name') ?? 'Друг';
        }
        // Если это нажатие на кнопку
        elseif ($callback) {
            $chatId = $callback->get('message')->get('chat')->get('id');
            $data = $callback->get('data');
            $username = $callback->get('from')->get('username');
            $firstName = $callback->get('from')->get('first_name') ?? 'Друг';
        }

        // Если не удалось определить ID чата — выходим
        if (!$chatId) return;

        // 1. Находим или создаем юзера (используя полученные переменные)
        $user = TelegramUser::firstOrCreate(
            ['chat_id' => $chatId],
            ['username' => $username, 'first_name' => $firstName]
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
        $today = \Carbon\Carbon::now();
        $startDate = \Carbon\Carbon::parse('2026-01-01'); // Убедитесь, что год верный (следующий январь)

        // 1. Приветствие (отправляется всегда)
        $welcomeText = "Привет, {$user->first_name}! 🎄\n\nЯ — новогодний бот-адвент. ";

        // 2. Если ивент еще не начался
        if ($today->lt($startDate)) {
            $welcomeText .= "Наш праздничный марафон начнется <b>1 января</b>! Заходи в первый день года, тебя будут ждать интересные задания, игры и подарки. До встречи! 🎅❄️";

            $photoPath = storage_path('app/images/welcome.png');

            return $this->telegram->sendPhoto([
                'chat_id' => $user->chat_id,
                'photo'   => InputFile::create($photoPath),
                'caption' => $welcomeText,
                'parse_mode' => 'HTML'
            ]);
        }

        // 3. Если ивент уже прошел (после 11 января)
        if ($today->day > 11 && $today->month == 1 || $today->month > 1) {
            return $this->summarizeResults($user);
        }

        // 4. Если сейчас время ивента (1-11 января)
        $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => $welcomeText . "Сегодня уже {$today->format('d.m')}, и мы начинаем! 🎁"
        ]);

        $this->giveNextTask($user);
    }

    protected function giveNextTask(TelegramUser $user)
    {
        $today = \Carbon\Carbon::today();

        // Ищем задания на СЕГОДНЯ, которые юзер еще НЕ выполнил
        $doneTaskIds = $user->completedTasks()->pluck('task_id');

        $nextTask = \App\Models\Task::whereDate('active_date', $today)
            ->whereNotIn('id', $doneTaskIds)
            ->orderBy('sort_order')
            ->first();

        if (!$nextTask) {
            $user->update(['current_task_id' => null]);
            $this->telegram->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => "<b>На сегодня заданий больше нет!</b> Отдыхай и приходи завтра ❄️\n\n🏆 Твой текущий счет: <b>{$user->total_score}</b> баллов.",
                'parse_mode' => 'HTML'
            ]);
            return;
        }

        // Назначаем текущее задание
        $user->update(['current_task_id' => $nextTask->id]);

        $keyboard = null;

        // 1. Логика для кнопок (выбор варианта)
        if ($nextTask->type === 'button') {
            $options = explode('|', $nextTask->options);
            $inlineButtons = [];

            foreach ($options as $option) {
                // callback_data будет содержать текст ответа
                $inlineButtons[] = [
                    ['text' => $option, 'callback_data' => 'ans_' . $option]
                ];
            }

            $keyboard = json_encode(['inline_keyboard' => $inlineButtons]);
        }
        // 2. Логика для простых действий (если остались задачи типа action)
        elseif ($nextTask->type === 'action') {
            $keyboard = json_encode([
                'inline_keyboard' => [[
                    ['text' => "✅ Выполнил!", 'callback_data' => 'task_done_' . $nextTask->id]
                ]]
            ]);
        }

        // Текст сложности для наглядности
        $difficulty = match ($nextTask->points) {
            5 => "🟢 Легко",
            10 => "🟡 Средне",
            15 => "🔴 Сложно",
            default => ""
        };

        $messageText = "🎁 <b>Задание №{$nextTask->sort_order}</b> ({$difficulty})\n\n" .
            $nextTask->question;

        if ($nextTask->type === 'text') {
            $messageText .= "\n\n<i>Напиши ответ сообщением ниже...</i>";
        }

        $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => $messageText,
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ]);
    }

    protected function checkAnswer(TelegramUser $user, $text)
    {
        $task = Task::find($user->current_task_id);
        $userAnswer = trim(mb_strtolower($text));

        // Разбиваем правильные ответы по запятой
        $validAnswers = explode(',', mb_strtolower($task->correct_answer));

        if (in_array($userAnswer, $validAnswers)) {
            $this->completeTask($user, $task);
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => "❌ Не совсем так! Попробуй еще раз или используй другое слово."
            ]);
        }
    }

    protected function handleCallback($user, $data)
    {
        $this->telegram->answerCallbackQuery([
            'callback_query_id' => $this->telegram->getWebhookUpdate()->getCallbackQuery()->get('id'),
        ]);
        if (str_starts_with($data, 'ans_')) {
            $answer = str_replace('ans_', '', $data);
            $task = Task::find($user->current_task_id);

            if ($task && $answer === $task->correct_answer) {
                return $this->completeTask($user, $task);
            } else {
                return $this->telegram->sendMessage([
                    'chat_id' => $user->chat_id,
                    'text' => "❌ Неверный вариант. Попробуй другой!"
                ]);
            }
        }

        if (str_starts_with($data, 'task_done_')) {
            $taskId = str_replace('task_done_', '', $data);

            // Проверка, что юзер выполняет именно это задание
            if ($user->current_task_id != $taskId) {
                return; // Игнорируем старые кнопки
            }

            $task = Task::find($taskId);
            $this->completeTask($user, $task);
        }
        $this->telegram->editMessageReplyMarkup([
            'chat_id' => $user->chat_id,
            'message_id' => $this->telegram->getWebhookUpdate()->getCallbackQuery()->get('message')->get('message_id'),
            'reply_markup' => json_encode(['inline_keyboard' => []]) // Удаляем кнопки
        ]);
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
        $rewardMsg = "✅ <b>Верно!</b> Ты получил +{$task->points} баллов.";
        if ($task->reward_content) {
            $rewardMsg .= "\n\n🎁 Твой бонус:\n" . $task->reward_content;
        }

        $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => $rewardMsg,
            'parse_mode' => 'HTML'
        ]);

        // 5. Сразу даем следующее задание (если есть)
        sleep(1); // Небольшая пауза для естественности
        $this->giveNextTask($user);
    }

    protected function summarizeResults($user)
    {
        // Логика подведения итогов
        $this->telegram->sendMessage([
            'chat_id' => $user->chat_id,
            'text' => "🏁 Ивент завершен! Ты набрал {$user->total_score} баллов. Жди информацию о главном призе!"
        ]);
    }
}
