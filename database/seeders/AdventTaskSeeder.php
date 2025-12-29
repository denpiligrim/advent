<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Добавьте этот фасад
use Carbon\Carbon;

class AdventTaskSeeder extends Seeder
{
    public function run()
    {
        // 1. Отключаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Очищаем таблицы (лучше очистить обе, чтобы сбросить прогресс)
        DB::table('user_tasks')->truncate();
        Task::truncate();

        // 3. Включаем проверку обратно
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tasks = [
            // ... ваши задания из предыдущего шага ...
            [
                'active_date' => '2025-01-01',
                'sort_order' => 1,
                'type' => 'text',
                'question' => '🎄 С Новым Годом! Как зовут внучку Деда Мороза?',
                'correct_answer' => 'Снегурочка',
                'points' => 10,
                'reward_content' => 'Промокод: START2025'
            ],
            // и так далее
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }
    }
}