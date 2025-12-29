<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Таблица пользователей
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id')->unique(); // ID чата в Telegram
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->integer('total_score')->default(0); // Общий счет
            $table->unsignedBigInteger('current_task_id')->nullable(); // На каком вопросе сейчас висит юзер
            $table->timestamps();
        });

        // Таблица заданий
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->date('active_date'); // Дата, когда задание доступно (2025-01-01 и т.д.)
            $table->integer('sort_order')->default(1); // Порядок задания внутри дня (1, 2, 3)
            $table->string('type')->default('text'); // 'text' (ввод ответа) или 'action' (кнопка)
            $table->text('question'); // Текст задания
            $table->string('correct_answer')->nullable(); // Правильный ответ (для проверки)
            $table->integer('points')->default(10); // Баллы за выполнение
            $table->text('reward_content')->nullable(); // Бонус (промокод, ссылка), который придет ПОСЛЕ ответа
            $table->timestamps();
        });

        // Таблица прогресса
        Schema::create('user_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('telegram_user_id')->constrained('telegram_users')->onDelete('cascade');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tasks');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('telegram_users');
    }
};