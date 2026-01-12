<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramUser;
use App\Models\Reward;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Illuminate\Support\Facades\Log;

class SendAdventRewards extends Command
{
    /**
     * Имя команды для запуска в терминале.
     *
     * @var string
     */
    protected $signature = 'advent:send-rewards';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Рассылка наград пользователям, набравшим 100+ баллов';

    protected $telegram;

    public function __construct(Api $telegram)
    {
        parent::__construct();
        $this->telegram = $telegram;
    }

    public function handle()
    {
        $this->info('Начинаю поиск победителей...');

        // 1. Получаем пользователей с баллом >= 100
        // Можно добавить whereDoesntHave, чтобы исключить тех, кто уже получил награду
        $users = TelegramUser::where('total_score', '>=', 100)->get();

        $count = 0;

        foreach ($users as $user) {
            // 2. Проверяем, есть ли у пользователя уже выданная награда
            $existingReward = Reward::where('tg_user_id', $user->id)->exists();
            if ($existingReward) {
                $this->info("Пользователь {$user->username} (ID: {$user->id}) уже получил награду. Пропускаем.");
                continue;
            }

            // 3. Берем свободный ключ
            // Используем lockForUpdate() для защиты от гонки процессов (если команда запустится дважды)
            $reward = Reward::where('status', 0)->lockForUpdate()->first();

            if (!$reward) {
                $this->error('ЗАКОНЧИЛИСЬ СВОБОДНЫЕ КЛЮЧИ! Рассылка остановлена.');
                return;
            }

            // 4. Формируем текст сообщения
            // Тег <code> делает текст моноширинным и кликабельным для копирования
            $caption = "<b>🎄Награда за участие в адвент-календаре! 🎁</b>\n\n" .
                "Хо-хо-хо! Спасибо за участие, ты набрал <b>{$user->total_score}</b> баллов и получил приз: доступ к ВПН на 1 месяц. Скопируй ссылку:\n\n" .
                "<code>{$reward->link}</code>\n\n" .
                "и вставь ее в приложении Happ, V2rayTUN или Hiddify, чтобы пользоваться VPN на любом устройстве!\n\n" .
                "Ждем тебя в следующем приключении, я оповещу тебя, когда ивент начнется.";

            try {
                // Путь к картинке с наградой (укажите свой путь в конфиге или жестко пропишите)
                // Например: 'storage/images/reward_final.jpg' или config('advent.images.final_reward')
                $photoPath = config('advent.images.final_reward');

                $this->telegram->sendPhoto([
                    'chat_id' => $user->chat_id,
                    'photo'   => $photoPath,
                    'caption' => $caption,
                    'parse_mode' => 'HTML'
                ]);

                // 5. Обновляем статус награды
                $reward->update([
                    'status' => 1,
                    'tg_user_id' => $user->id
                ]);

                $this->info("Награда отправлена пользователю: {$user->chat_id}");
                $count++;

                // Небольшая задержка, чтобы не спамить в API Телеграма
                usleep(300000); // 0.3 секунды

            } catch (\Exception $e) {
                $this->error("Ошибка отправки для {$user->chat_id}: " . $e->getMessage());
                Log::error("Advent Reward Error User {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("Рассылка завершена! Всего отправлено наград: {$count}");
    }
}
