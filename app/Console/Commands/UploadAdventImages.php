<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Illuminate\Support\Facades\File;

class UploadAdventImages extends Command
{
    // Команда для запуска: php artisan advent:upload-images {chat_id}
    protected $signature = 'advent:upload-images {chat_id}';
    protected $description = 'Загружает картинки в Telegram и возвращает их file_id';

    public function handle()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $chatId = $this->argument('chat_id');

        // Путь к вашей папке с картинками
        $directory = storage_path('app/images');

        if (!File::exists($directory)) {
            $this->error("Папка не найдена: $directory");
            return;
        }

        $files = File::files($directory);
        $this->info("Найдено файлов: " . count($files));

        $results = [];

        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $this->line("Загружаю: $fileName...");

            try {
                $response = $telegram->sendPhoto([
                    'chat_id' => $chatId,
                    'photo'   => InputFile::create($file->getPathname()),
                    'caption' => "Файл: $fileName"
                ]);

                // Проверяем, что ответ — это объект сообщения, и в нем есть фото
                if ($response && isset($response['photo'])) {
                    $photo = $response['photo'];

                    // Telegram присылает массив разных размеров. 
                    // Мы берем последний элемент — это самое высокое качество.
                    $lastPhoto = end($photo);
                    $fileId = $lastPhoto['file_id'];

                    $results[$fileName] = $fileId;
                    $this->info("Успешно: $fileId");
                } else {
                    $this->error("Ошибка: Telegram вернул пустой ответ для $fileName");
                }

                sleep(1);
            } catch (\Exception $e) {
                $this->error("Ошибка при загрузке $fileName: " . $e->getMessage());
            }
        }

        $this->info("\n--- СКОПИРУЙТЕ ЭТОТ МАССИВ В ВАШ КОД --- \n");

        // Выводим в формате PHP массива
        echo "[\n";
        foreach ($results as $name => $id) {
            echo "    '$name' => '$id',\n";
        }
        echo "];\n";
    }
}
